import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', function () {
    if (!document.getElementById('relocationForm')) return;

    // ------------------------------------------------------------------
    // ELEMENTS
    // ------------------------------------------------------------------
    const fromSite = document.getElementById('from_rssite');
    const fromWhse = document.getElementById('from_rswhse');
    const fromBay = document.getElementById('from_rsbaynum');
    const fromLoc = document.getElementById('from_rsloc');
    const fromPallet = document.getElementById('from_rspallet');
    const fromJob = document.getElementById('from_jobco');

    const toSite = document.getElementById('to_rssite');
    const toWhse = document.getElementById('to_rswhse');
    const toBay = document.getElementById('to_rsbaynum');
    const toLoc = document.getElementById('to_rsloc');
    const toPallet = document.getElementById('to_rspallet');
    const toQty = document.getElementById('qty_to_move');
    const docNo = document.getElementById('docno');

    const fromItem = document.getElementById('from_item');
    const fromDesc = document.getElementById('from_desc');
    const fromQty = document.getElementById('from_qty');
    const fromUm = document.getElementById('from_um');

    const suggestions = document.getElementById('fromLocationSuggestions');

    let fromLocations = [];

    // ------------------------------------------------------------------
    // HELPERS
    // ------------------------------------------------------------------
    function setDisabled(element, disabled) {
        if (element) element.disabled = disabled;
    }

    function resetSelect(select, placeholder) {
        if (!select) return;
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.value = '';
    }

    function isFloorBay() {
        return String(fromBay.value).trim().toUpperCase() === 'FLOOR';
    }

    function getJson(url, params) {
        return fetch(url + '?' + new URLSearchParams(params))
            .then(response => {
                if (!response.ok) throw new Error('Request failed: ' + response.status);
                return response.json();
            });
    }

    // Ignore responses that arrive after a newer request was made
    const requestIds = new WeakMap();

    function nextRequestId(key) {
        const id = (requestIds.get(key) || 0) + 1;
        requestIds.set(key, id);
        return id;
    }

    function isLatest(key, id) {
        return requestIds.get(key) === id;
    }

    // Fill a <select> and keep the current selection if it still exists
    function fillSelect(select, placeholder, values, labelFn = v => v) {
        const previous = select.value;

        select.innerHTML = `<option value="">${placeholder}</option>`;

        values.forEach(value => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = labelFn(value);
            select.appendChild(option);
        });

        if (previous && values.includes(previous)) {
            select.value = previous;
        }
    }

    function clearItemInfo() {
        fromItem.value = '';
        fromDesc.value = '';
        fromQty.value = '';
        fromUm.value = '';
        toQty.value = '';
        docNo.value = '';
    }

    function hideSuggestions() {
        suggestions.innerHTML = '';
        suggestions.style.display = 'none';
    }

    // Clears everything below the given FROM level
    function resetFrom(level) {
        const order = ['bay', 'loc', 'pallet', 'job'];
        const start = order.indexOf(level);

        if (start <= 0) {
            resetSelect(fromBay, 'Choose Bay No...');
            setDisabled(fromBay, true);
        }
        if (start <= 1) {
            fromLoc.value = '';
            setDisabled(fromLoc, true);
            fromLocations = [];
            hideSuggestions();
        }
        if (start <= 2) {
            resetSelect(fromPallet, 'Choose Pallet No...');
            setDisabled(fromPallet, true);
            toPallet.value = '';
        }
        if (start <= 3) {
            resetSelect(fromJob, 'Choose Job / CO...');
            setDisabled(fromJob, true);
            clearItemInfo();
        }
    }

    function filterWarehouse(site, warehouse) {
        [...warehouse.options].forEach(option => {
            if (option.value) {
                option.hidden = option.dataset.site !== site.value;
            }
        });
    }

    // ------------------------------------------------------------------
    // INITIAL STATE
    // ------------------------------------------------------------------
    function initializeFields() {
        setDisabled(fromSite, false);
        setDisabled(fromWhse, false);
        setDisabled(fromBay, true);
        setDisabled(fromLoc, true);
        setDisabled(fromPallet, true);
        setDisabled(fromJob, true);

        setDisabled(toSite, false);
        setDisabled(toWhse, false);
        setDisabled(toBay, true);
        setDisabled(toLoc, true);
        setDisabled(toPallet, true);

        setDisabled(fromItem, true);
        setDisabled(fromDesc, true);
        setDisabled(fromQty, true);
        setDisabled(fromUm, true);
        setDisabled(toQty, true);
    }

    initializeFields();

    // ------------------------------------------------------------------
    // GENERIC DROPDOWN LOADER (bays + TO locations)
    // ------------------------------------------------------------------
    function load(select, url, data, text) {
        const id = nextRequestId(select);

        return getJson(url, data)
            .then(result => {
                if (!isLatest(select, id)) return result;

                const previous = select.value;

                select.innerHTML = `<option value="">${text}</option>`;

                result.forEach(item => {
                    const value =
                        item.rsbaynum ??
                        item.rsloc ??
                        item.rspallet_num ??
                        item.job;

                    let displayText = value;

                    // TO LOCATION: only empty locations or same item with space left
                    if (select === toLoc) {
                        if (item.status === 'empty') {
                            displayText = `${value} - Empty`;
                        } else if (item.status === 'same_item') {
                            const availableQty = Number(item.available_qty || 0);
                            const locationLimit = Number(item.location_limit || 0);

                            if (locationLimit > 0 && availableQty >= locationLimit) {
                                return; // already full
                            }

                            const palletText = item.pallet_numbers
                                ? ` - Pallet: ${item.pallet_numbers}`
                                : '';

                            displayText =
                                `${value} - ${availableQty.toLocaleString()}/${locationLimit.toLocaleString()}${palletText}`;
                        }
                    }

                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = displayText;
                    select.appendChild(option);
                });

                // Keep the user's selection if it is still available
                if (previous && [...select.options].some(o => o.value === previous)) {
                    select.value = previous;
                }

                return result;
            })
            .catch(error => {
                console.error('Error loading dropdown:', error);
                return [];
            });
    }

    // ------------------------------------------------------------------
    // FROM LOCATION SUGGESTIONS
    // ------------------------------------------------------------------
    function loadFromLocations(extraData = {}) {
        const id = nextRequestId(suggestions);

        fromLocations = [];
        hideSuggestions();

        return getJson(goodsRelocatingRoutes.locations, {
            rssite: fromSite.value,
            rswhse: fromWhse.value,
            rsbaynum: fromBay.value,
            occupied_only: 1,
            ...extraData
        })
            .then(result => {
                if (!isLatest(suggestions, id)) return fromLocations;
                fromLocations = result;
                return result;
            })
            .catch(error => {
                console.error('Error loading locations:', error);
                return [];
            });
    }

    function renderSuggestions(list) {
        suggestions.innerHTML = '';

        if (list.length === 0) {
            suggestions.style.display = 'none';
            return;
        }

        list.forEach(item => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'list-group-item list-group-item-action';
            button.textContent = item.rsloc;

            button.addEventListener('click', function () {
                fromLoc.value = item.rsloc;
                hideSuggestions();
                fromLoc.dispatchEvent(new Event('change'));
            });

            suggestions.appendChild(button);
        });

        suggestions.style.display = 'block';
    }

    function findLocation(value) {
        const search = String(value || '').trim().toLowerCase();
        return fromLocations.find(
            item => String(item.rsloc || '').toLowerCase() === search
        );
    }

    // ------------------------------------------------------------------
    // JOB LOADER (auto-select when there is only one)
    // ------------------------------------------------------------------
    function loadJobs(palletValue) {
        const id = nextRequestId(fromJob);

        return getJson(goodsRelocatingRoutes.jobs, {
            rssite: fromSite.value,
            rswhse: fromWhse.value,
            rsloc: fromLoc.value,
            rspallet: palletValue || ''
        })
            .then(result => {
                if (!isLatest(fromJob, id)) return result;

                const jobs = result.map(r => r.job);

                fillSelect(fromJob, 'Choose Job / CO...', jobs);
                setDisabled(fromJob, jobs.length === 0);

                if (jobs.length === 1 && !fromJob.value) {
                    fromJob.value = jobs[0];
                    fromJob.dispatchEvent(new Event('change'));
                }

                return jobs;
            })
            .catch(error => {
                console.error('Error loading jobs:', error);
                return [];
            });
    }

    // ------------------------------------------------------------------
    // TO LOCATIONS (refreshed whenever bay or item changes)
    // ------------------------------------------------------------------
    function refreshToLocations() {
        if (!toBay.value) return;

        setDisabled(toLoc, false);
        setDisabled(toPallet, false);
        setDisabled(toQty, false);
        setDisabled(docNo, false);

        load(
            toLoc,
            goodsRelocatingRoutes.locations,
            {
                rssite: toSite.value,
                rswhse: toWhse.value,
                rsbaynum: toBay.value,
                to_location: 1,
                item: fromItem.value,
                from_rsloc: fromLoc.value
            },
            'Choose Empty Location...'
        );
    }

    // ==================================================================
    // FROM
    // ==================================================================
    fromSite.addEventListener('change', function () {
        resetFrom('bay');
        filterWarehouse(fromSite, fromWhse);
        fromWhse.value = '';
    });

    fromWhse.addEventListener('change', function () {
        resetFrom('bay');

        if (!fromWhse.value) return;

        setDisabled(fromBay, false);

        load(
            fromBay,
            goodsRelocatingRoutes.bays,
            { rssite: fromSite.value, rswhse: fromWhse.value },
            'Choose Bay No...'
        );
    });

    fromBay.addEventListener('change', async function () {
        resetFrom('loc');

        if (!fromBay.value) return;

        // ----------------------------------------------------------
        // FLOOR: BAY -> PALLET -> LOCATION -> JOB
        // ----------------------------------------------------------
        if (isFloorBay()) {
            setDisabled(fromLoc, true);
            setDisabled(fromPallet, false);

            load(
                fromPallet,
                goodsRelocatingRoutes.pallets,
                {
                    rssite: fromSite.value,
                    rswhse: fromWhse.value,
                    rsbaynum: fromBay.value
                },
                'Choose Pallet No...'
            );

            return;
        }

        // ----------------------------------------------------------
        // NORMAL: BAY -> LOCATION -> PALLET -> JOB
        // ----------------------------------------------------------
        setDisabled(fromLoc, false);
        fromLoc.value = `${fromBay.value}-`;

        const bayAtRequest = fromBay.value;
        await loadFromLocations();

        // Bay changed while loading
        if (fromBay.value !== bayAtRequest) return;

        const prefix = fromLoc.value.trim().toLowerCase();

        renderSuggestions(
            fromLocations.filter(item =>
                String(item.rsloc || '').toLowerCase().startsWith(prefix)
            )
        );
    });

    // Typing in the location box filters the suggestions
    fromLoc.addEventListener('input', function () {
        const search = this.value.trim().toLowerCase();

        // Typing a new location invalidates the pallet / job below it (normal only)
        if (!isFloorBay()) {
            resetFrom('pallet');
        } else {
            resetFrom('job');
        }

        if (!search) {
            renderSuggestions(fromLocations);
            return;
        }

        renderSuggestions(
            fromLocations.filter(item =>
                String(item.rsloc || '').toLowerCase().includes(search)
            )
        );
    });

    fromLoc.addEventListener('focus', function () {
        if (fromLoc.disabled || fromLocations.length === 0) return;

        const search = fromLoc.value.trim().toLowerCase();

        renderSuggestions(
            search
                ? fromLocations.filter(item =>
                      String(item.rsloc || '').toLowerCase().includes(search))
                : fromLocations
        );
    });

    fromLoc.addEventListener('change', async function () {

        // Only continue when the typed value is a real location
        const match = findLocation(fromLoc.value);

        if (!match) {
            if (isFloorBay()) {
                resetFrom('job');
            } else {
                resetFrom('pallet');
            }
            return;
        }

        fromLoc.value = match.rsloc;
        hideSuggestions();

        // ----------------------------------------------------------
        // FLOOR: the pallet was already chosen. NEVER touch it here.
        // ----------------------------------------------------------
        if (isFloorBay()) {
            if (!fromPallet.value) {
                resetFrom('job');
                return;
            }

            resetFrom('job');
            loadJobs(fromPallet.value);
            return;
        }

        // ----------------------------------------------------------
        // NORMAL: LOCATION -> PALLET -> JOB
        // ----------------------------------------------------------
        resetFrom('pallet');

        const locAtRequest = fromLoc.value;

        try {
            const [palletRows, noPalletJobs] = await Promise.all([
                getJson(goodsRelocatingRoutes.pallets, {
                    rssite: fromSite.value,
                    rswhse: fromWhse.value,
                    rsbaynum: fromBay.value,
                    rsloc: locAtRequest
                }),
                getJson(goodsRelocatingRoutes.jobs, {
                    rssite: fromSite.value,
                    rswhse: fromWhse.value,
                    rsloc: locAtRequest,
                    rspallet: ''
                })
            ]);

            // Location changed while loading
            if (fromLoc.value !== locAtRequest) return;

            const pallets = palletRows.map(r => r.rspallet_num);
            const hasNoPalletItems = noPalletJobs.length > 0;

            // No pallets at this location -> go straight to Job
            if (pallets.length === 0) {
                resetSelect(fromPallet, 'No Pallet');
                setDisabled(fromPallet, true);
                loadJobs('');
                return;
            }

            setDisabled(fromPallet, false);

            fillSelect(
                fromPallet,
                hasNoPalletItems ? 'No Pallet / Choose Pallet...' : 'Choose Pallet No...',
                pallets
            );

            if (hasNoPalletItems) {
                // Items without a pallet also exist here
                loadJobs('');
            } else if (pallets.length === 1) {
                // Only one choice, so select it for the user
                fromPallet.value = pallets[0];
                fromPallet.dispatchEvent(new Event('change'));
            }
            // else: several pallets, wait for the user to choose one

        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to load pallets for this location.'
            });
        }
    });

    fromPallet.addEventListener('change', async function () {

        toPallet.value = fromPallet.value || '';

        resetFrom('job');

        // ----------------------------------------------------------
        // FLOOR: PALLET -> LOCATION -> JOB
        // ----------------------------------------------------------
        if (isFloorBay()) {
            fromLoc.value = '';
            fromLocations = [];
            hideSuggestions();

            if (!fromPallet.value) {
                setDisabled(fromLoc, true);
                return;
            }

            setDisabled(fromLoc, false);

            const palletAtRequest = fromPallet.value;

            // ONLY locations that contain this pallet
            await loadFromLocations({ rspallet: palletAtRequest });

            if (fromPallet.value !== palletAtRequest) return;

            if (fromLocations.length === 1) {
                // Only one location holds this pallet, so select it
                fromLoc.value = fromLocations[0].rsloc;
                fromLoc.dispatchEvent(new Event('change'));
            } else {
                renderSuggestions(fromLocations);
            }

            return;
        }

        // ----------------------------------------------------------
        // NORMAL: LOCATION -> PALLET -> JOB
        // ----------------------------------------------------------
        if (!fromLoc.value) return;

        loadJobs(fromPallet.value || '');
    });

    fromJob.addEventListener('change', function () {

        if (!fromJob.value) {
            clearItemInfo();
            return;
        }

        fetch(
            goodsRelocatingRoutes.item + '?' +
            new URLSearchParams({
                rssite: fromSite.value,
                rswhse: fromWhse.value,
                rsloc: fromLoc.value,
                rspallet: fromPallet.value || '',
                job: fromJob.value
            })
        )
            .then(response => response.json())
            .then(item => {

                fromItem.value = item.item ?? '';
                fromQty.value = parseFloat(item.qty || 0).toFixed(2);
                fromUm.value = item.um ?? '';
                fromDesc.value = item.desc ?? '';
                docNo.value = item.docno ?? '';

                toQty.value = parseFloat(item.qty || 0).toFixed(2);
                toQty.max = item.qty ?? '';

                // Item is now known, so refresh the TO locations
                refreshToLocations();
            })
            .catch(error => {
                console.error(error);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to fetch item information.'
                });
            });
    });

    // ==================================================================
    // TO
    // ==================================================================
    toSite.addEventListener('change', function () {
        resetSelect(toBay, 'Choose Bay No...');
        resetSelect(toLoc, 'Choose Empty Location...');

        setDisabled(toBay, true);
        setDisabled(toLoc, true);

        filterWarehouse(toSite, toWhse);
        toWhse.value = '';
    });

    toWhse.addEventListener('change', function () {
        resetSelect(toLoc, 'Choose Empty Location...');
        setDisabled(toLoc, true);

        if (!toWhse.value) {
            resetSelect(toBay, 'Choose Bay No...');
            setDisabled(toBay, true);
            return;
        }

        setDisabled(toBay, false);

        load(
            toBay,
            goodsRelocatingRoutes.bays,
            { rssite: toSite.value, rswhse: toWhse.value },
            'Choose Bay No...'
        );
    });

    toBay.addEventListener('change', function () {
        if (!toBay.value) {
            resetSelect(toLoc, 'Choose Empty Location...');
            setDisabled(toLoc, true);
            return;
        }

        refreshToLocations();
    });

    // ==================================================================
    // MOVE ITEM
    // ==================================================================
    function moveItem() {

        const data = {
            from_rssite: fromSite.value,
            from_rswhse: fromWhse.value,
            from_rsbaynum: fromBay.value,
            from_rsloc: fromLoc.value,
            from_rspallet: fromPallet.value || '',
            from_jobco: fromJob.value,

            to_rssite: toSite.value,
            to_rswhse: toWhse.value,
            to_rsbaynum: toBay.value,
            to_rsloc: toLoc.value,
            to_rspallet: toPallet.value,
            qty_to_move: toQty.value,
            docno: docNo.value
        };

        fetch(goodsRelocatingRoutes.move, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                ).getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(async response => {
                const result = await response.json();

                console.log('Move response:', result);

                if (!response.ok) {
                    throw new Error(result.message || 'Server error occurred.');
                }

                return result;
            })
            .then(result => {
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Moved Successful',
                        text: result.message,
                        timer: 1800,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cannot Move the Item',
                        text: result.message || 'Relocation failed.'
                    });
                }
            })
            .catch(error => {
                console.error('Move Item Error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Cannot Move the Item',
                    text: error.message || 'An error occurred while moving the item.',
                    confirmButtonColor: '#dc3545'
                });
            });
    }

    // ==================================================================
    // CONFIRM RELOCATION
    // ==================================================================
    function confirmRelocation() {

        const itemValue = fromItem.value;
        const descValue = fromDesc.value;
        const umValue = fromUm.value;

        const fromPalletValue = fromPallet.value;
        const toPalletValue = toPallet.value;
        const qtyToMove = toQty.value;
        const docNoValue = docNo.value.trim();

        if (
            !fromSite.value ||
            !fromWhse.value ||
            !fromBay.value ||
            !fromLoc.value ||
            !fromJob.value
        ) {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Source Information',
                text: 'Please complete the source location details first.'
            });
            return;
        }

        if (
            !toSite.value ||
            !toWhse.value ||
            !toBay.value ||
            !toLoc.value
        ) {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Destination Information',
                text: 'Please complete the destination location details first.'
            });
            return;
        }

        if (!qtyToMove || parseFloat(qtyToMove) <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Quantity',
                text: 'Please enter a valid quantity to move.'
            });
            return;
        }

        Swal.fire({
            title: 'Confirm Relocation',
            html: `
        <div>

            <!-- ITEM SUMMARY -->
            <div class="text-start p-3 mb-3 rounded-3 bg-light border">
                <div class="text-uppercase text-secondary small fw-semibold mb-1">Item</div>
                <div class="fw-bold fs-5 text-dark">${itemValue}</div>
                <div class="fw-bold fs-5 text-danger"> ${fromJob.value}</div>
                <div class="text-secondary small">${descValue}</div>
            </div>

            <!-- FROM / TO -->
            <div class="d-flex align-items-center justify-content-center gap-3 p-3 mb-3 rounded-3 border border-warning-subtle bg-warning-subtle">
                <div class="text-center">
                    <div class="text-uppercase text-warning-emphasis fw-semibold small">From</div>
                    <strong class="text-dark fs-6">${fromLoc.value}</strong>
                    ${fromPalletValue ? `<div class="text-secondary small">Pallet ${fromPalletValue}</div>` : ''}
                </div>

                <div class="d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width:36px;height:36px;">
                    <i class="bi bi-arrow-right text-warning-emphasis"></i>
                </div>

                <div class="text-center">
                    <div class="text-uppercase text-warning-emphasis fw-semibold small">To</div>
                    <strong class="text-dark fs-6">${toLoc.value}</strong>
                    ${toPalletValue ? `<div class="text-secondary small">Pallet ${toPalletValue}</div>` : ''}
                </div>
            </div>

            <!-- QUANTITY -->
            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border">
                <span class="text-secondary small text-uppercase fw-semibold">Quantity to Move</span>
                <span class="badge rounded-pill text-bg-primary px-3 py-2 fs-6">${qtyToMove} ${umValue}</span>
            </div>

            ${docNoValue ? `
            <div class="text-center text-secondary small mt-2">
                Document No. <strong>${docNoValue}</strong>
            </div>` : ''}

            <div class="text-center text-muted small mt-3">
                <i class="bi bi-info-circle me-1"></i>
                Please review before proceeding.
            </div>

        </div>
        `,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-check-circle-fill me-1"></i> Proceed',
            cancelButtonText: '<i class="bi bi-x-circle me-1"></i> Cancel',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#fc7b7b',
            width: '480px',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                moveItem();
            }
        });
    }

    document.getElementById('moveItemBtn')
        .addEventListener('click', confirmRelocation);


// ==================================================================
// FIELD GUIDE (highlights the next field to fill)
// ==================================================================
const guideFields = [
    fromSite, fromWhse, fromBay, fromLoc, fromPallet, fromJob,
    toSite, toWhse, toBay, toLoc
];

function isFilled(el) {
    // fromLoc is pre-filled with "BAY-", so only count a real location
    if (el === fromLoc) return !!findLocation(el.value);
    return !!el.value;
}

function getGuideOrder() {
    const from = isFloorBay()
        ? [fromSite, fromWhse, fromBay, fromPallet, fromLoc, fromJob]   // FLOOR
        : [fromSite, fromWhse, fromBay, fromLoc, fromPallet, fromJob];  // NORMAL

    return [...from, toSite, toWhse, toBay, toLoc];
}

function updateGuide() {
      // Green check on every field that is already filled
    guideFields.forEach(el => {
        el.classList.toggle('guide-done', isFilled(el));
    });
    guideFields.forEach(el => el.classList.remove('guide-highlight'));
    document.querySelectorAll('label.guide-label')
        .forEach(l => l.classList.remove('guide-label'));

    const normal = !isFloorBay();

    for (const el of getGuideOrder()) {
        if (isFilled(el)) continue;

        if (el === fromPallet && normal && !fromJob.disabled) continue;

        if (el.disabled) {
            if (el === fromPallet) continue;
            return;
        }

        el.classList.add('guide-highlight');

        const label = document.querySelector(`label[for="${el.id}"]`);
        if (label) label.classList.add('guide-label');

        // Only scroll if the field is outside the visible area
        const rect = el.getBoundingClientRect();
        if (rect.top < 80 || rect.bottom > window.innerHeight - 80) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return;
    }
}

let guideQueued = false;
function scheduleGuide() {
    if (guideQueued) return;
    guideQueued = true;
    requestAnimationFrame(() => {
        guideQueued = false;
        updateGuide();
    });
}

// Update on user interaction
guideFields.forEach(el => {
    el.addEventListener('change', scheduleGuide);
    el.addEventListener('input', scheduleGuide);
});

// Update after async loads (options filled, fields enabled, suggestions loaded)
const guideObserver = new MutationObserver(scheduleGuide);
guideFields.forEach(el => {
    guideObserver.observe(el, {
        childList: true,
        attributes: true,
        attributeFilter: ['disabled']
    });
});
guideObserver.observe(suggestions, { childList: true });

updateGuide();


    // Close suggestions when clicking outside
    document.addEventListener('click', function (event) {
        if (
            !fromLoc.contains(event.target) &&
            !suggestions.contains(event.target)
        ) {
            suggestions.style.display = 'none';
        }
    });

});




























