import Swal from "sweetalert2";



document.addEventListener('DOMContentLoaded', function () {

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



function setDisabled(element, disabled) {
    if (element) {
        element.disabled = disabled;
    }
}

function resetSelect(select, placeholder) {
    if (!select) return;

    select.innerHTML = `<option value="">${placeholder}</option>`;
    select.value = '';
}

function resetInput(input) {
    if (!input) return;

    input.value = '';
}



function initializeFields() {

    // FROM
    setDisabled(fromSite, false);
    setDisabled(fromWhse, false);

    setDisabled(fromBay, true);
    setDisabled(fromLoc, true);
    setDisabled(fromPallet, true);
    setDisabled(fromJob, true);

    // TO
    setDisabled(toSite, false);
    setDisabled(toWhse, false);

    setDisabled(toBay, true);
    setDisabled(toLoc, true);

    setDisabled(
        document.getElementById('to_rspallet'),
        true
    );

    setDisabled(
        document.getElementById('from_item'),
        true
    );

    setDisabled(
        document.getElementById('from_desc'),
        true
    );

    setDisabled(
        document.getElementById('from_qty'),
        true
    );

    setDisabled(
        document.getElementById('from_um'),
        true
    );

    setDisabled(
        document.getElementById('qty_to_move'),
        true
    );
        setDisabled(
        document.getElementById('qty_to_move'),
        true
    );
}

    initializeFields();
    

    // LOAD DROPDOWN

    function load(select, url, data, text) {

        fetch(url + '?' + new URLSearchParams(data))
            .then(response => response.json())
            .then(result => {

                select.innerHTML =
                    `<option value="">${text}</option>`;

                result.forEach(item => {

                    let value =
                        item.rsbaynum ??
                        item.rsloc ??
                        item.rspallet_num ??
                        item.job;

                    let displayText = value;

                    // TO LOCATION display
                    if (select === toLoc) {

                        if (item.status === 'empty') {

                            displayText = `${value} - Empty`;

                        } else if (item.status === 'same_item') {

                            let availableQty = Number(item.available_qty || 0);
                            let locationLimit = Number(item.location_limit || 0);

                            let palletText = item.pallet_numbers
                                ? ` - Pallet: ${item.pallet_numbers}`
                                : '';

                            displayText =
                                `${value} - ${availableQty.toLocaleString()}/${locationLimit.toLocaleString()}${palletText}`;
                        }
                    }

                    select.innerHTML += `
                        <option value="${value}">
                            ${displayText}
                        </option>
                    `;
                });
            });
    }



    function filterWarehouse(site, warehouse) {

        [...warehouse.options].forEach(option => {

            if (option.value) {
                option.hidden =
                    option.dataset.site !== site.value;
            }

        });
    }


    // FROM

    fromSite.addEventListener('change', function () {

        resetSelect(fromBay, 'Choose Bay No...');
        resetSelect(fromLoc, 'Choose Location...');
        resetSelect(fromPallet, 'Choose Pallet No...');
        resetSelect(fromJob, 'Choose Job / CO...');

        setDisabled(fromBay, true);
        setDisabled(fromLoc, true);
        setDisabled(fromPallet, true);
        setDisabled(fromJob, true);

        filterWarehouse(fromSite, fromWhse);

        // Reset warehouse
        fromWhse.value = '';

    });

    fromWhse.addEventListener('change', function () {

        // Reset fields below warehouse
        resetSelect(fromLoc, 'Choose Location...');
        resetSelect(fromPallet, 'Choose Pallet No...');
        resetSelect(fromJob, 'Choose Job / CO...');

        setDisabled(fromLoc, true);
        setDisabled(fromPallet, true);
        setDisabled(fromJob, true);

        // If warehouse was cleared
        if (!fromWhse.value) {

            resetSelect(fromBay, 'Choose Bay No...');
            setDisabled(fromBay, true);

            return;
        }

        // Enable Bay
        setDisabled(fromBay, false);

        load(
            fromBay,
            goodsRelocatingRoutes.bays,
            {
                rssite: fromSite.value,
                rswhse: fromWhse.value
            },
            'Choose Bay No...'
        );

    });


    fromBay.addEventListener('change', function () {
    // Reset lower fields
        resetSelect(fromPallet, 'Choose Pallet No...');
        resetSelect(fromJob, 'Choose Job / CO...');
         setDisabled(fromPallet, true);
    setDisabled(fromJob, true);

    if (!fromBay.value) {

        resetSelect(fromLoc, 'Choose Location...');
        setDisabled(fromLoc, true);

        return;
    }

    // Enable Location
    setDisabled(fromLoc, false);

        load(
            fromLoc,
            goodsRelocatingRoutes.locations,
            {
                rssite: fromSite.value,
                rswhse: fromWhse.value,
                rsbaynum: fromBay.value,
                occupied_only: 1
            },
            'Choose Occupied Location...'
        );

    });


    fromLoc.addEventListener('change', function () {

        resetSelect(fromPallet, 'No Pallet / Choose Pallet...');
        resetSelect(fromJob, 'Choose Job / CO...');

        if (!fromLoc.value) {
            setDisabled(fromPallet, true);
            setDisabled(fromJob, true);
            return;
        }

        setDisabled(fromPallet, false);

        setDisabled(fromJob, false);

        load(
            fromPallet,
            goodsRelocatingRoutes.pallets,
            {
                rssite: fromSite.value,
                rswhse: fromWhse.value,
                rsloc: fromLoc.value
            },
            'No Pallet / Choose Pallet...'
        );

        // LOAD JOBS WITHOUT REQUIRING PALLET
        load(
            fromJob,
            goodsRelocatingRoutes.jobs,
            {
                rssite: fromSite.value,
                rswhse: fromWhse.value,
                rsloc: fromLoc.value,
                rspallet: ''
            },
            'Choose Job / CO...'
        );
    });

fromPallet.addEventListener('change', function () {

    // Only copy pallet to destination if a pallet was selected
    document.getElementById('to_rspallet').value =
        fromPallet.value || '';

    resetSelect(fromJob, 'Choose Job / CO...');

    if (!fromLoc.value) {
        setDisabled(fromJob, true);
        return;
    }

    setDisabled(fromJob, false);

    // If pallet was selected:
    //     load jobs belonging to that pallet
    //
    // If pallet was NOT selected:
    //     load jobs without a pallet
    load(
        fromJob,
        goodsRelocatingRoutes.jobs,
        {
            rssite: fromSite.value,
            rswhse: fromWhse.value,
            rsloc: fromLoc.value,
            rspallet: fromPallet.value || ''
        },
        'Choose Job / CO...'
    );

});

    fromJob.addEventListener('change', function () {

        if (!fromJob.value) {
            document.getElementById('from_item').value = '';
            document.getElementById('from_qty').value = '';
            document.getElementById('from_um').value = '';
            document.getElementById('from_desc').value = '';
            document.getElementById('qty_to_move').value = '';
            document.getElementById('docno').value = '';

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

            // ITEM INFORMATION

            document.getElementById('from_item').value =
                item.item ?? '';

            document.getElementById('from_qty').value =
                parseFloat(item.qty || 0).toFixed(2);

            document.getElementById('from_um').value =
                item.um ?? '';

            document.getElementById('from_desc').value =
                item.desc ?? '';
            document.getElementById('docno').value =
                item.docno ?? '';

            // QUANTITY TO MOVE

            document.getElementById('qty_to_move').value =
                parseFloat(item.qty || 0).toFixed(2);

            document.getElementById('qty_to_move').max =
                item.qty ?? '';


            document.getElementById('docno').value =
                item.docno ?? '';

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

    // TO

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

        // Enable Bay
        setDisabled(toBay, false);

        load(
            toBay,
            goodsRelocatingRoutes.bays,
            {
                rssite: toSite.value,
                rswhse: toWhse.value
            },
            'Choose Bay No...'
        );

    });


    toBay.addEventListener('change', function () {

        if (!toBay.value) {

            resetSelect(toLoc, 'Choose Empty Location...');
            setDisabled(toLoc, true);

            return;
        }

        // Enable Location
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
                item: document.getElementById('from_item').value,
                from_rsloc: fromLoc.value

            },
            'Choose Empty Location...'
        );

    });






    // MOVE ITEM
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
            to_rspallet:
                document.getElementById('to_rspallet').value,
            qty_to_move:
                document.getElementById('qty_to_move').value,
            docno:
                document.getElementById('docno').value
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

// CONFIRM RELOCATION

function confirmRelocation() {

    const fromItem = document.getElementById('from_item').value;
    const fromDesc = document.getElementById('from_desc').value;
    const fromQty = document.getElementById('from_qty').value;
    const fromUm = document.getElementById('from_um').value;

    const fromSiteValue = fromSite.value;
    const fromWhseValue = fromWhse.value;
    const fromBayValue = fromBay.value;
    const fromLocValue = fromLoc.value;
    const fromPalletValue = fromPallet.value;
    const fromJobValue = fromJob.value;

    const toSiteValue = toSite.value;
    const toWhseValue = toWhse.value;
    const toBayValue = toBay.value;
    const toLocValue = toLoc.value;
    const toPalletValue =
        document.getElementById('to_rspallet').value;

    const qtyToMove =
        document.getElementById('qty_to_move').value;

    const docNo =
        document.getElementById('docno').value.trim();

    // Basic validation before showing confirmation
    if (
        !fromSiteValue ||
        !fromWhseValue ||
        !fromBayValue ||
        !fromLocValue ||
        // !fromPalletValue ||
        !fromJobValue
    ) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Source Information',
            text: 'Please complete the source location details first.'
        });

        return;
    }

    if (
        !toSiteValue ||
        !toWhseValue ||
        !toBayValue ||
        !toLocValue
        // !toPalletValue
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
                <div class="fw-bold fs-5 text-dark">${fromItem}</div>
                <div class="fw-bold fs-5 text-danger"> ${fromJobValue}</div>
                <div class="text-secondary small">${fromDesc}</div>

            </div>

            <!-- FROM / TO -->
            <div class="d-flex align-items-center justify-content-center gap-3 p-3 mb-3 rounded-3 border border-warning-subtle bg-warning-subtle">
                <div class="text-center">
                    <div class="text-uppercase text-warning-emphasis fw-semibold small">From</div>
                    <strong class="text-dark fs-6">${fromLocValue}</strong>
                    ${fromPalletValue ? `<div class="text-secondary small">Pallet ${fromPalletValue}</div>` : ''}
                </div>

                <div class="d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width:36px;height:36px;">
                    <i class="bi bi-arrow-right text-warning-emphasis"></i>
                </div>

                <div class="text-center">
                    <div class="text-uppercase text-warning-emphasis fw-semibold small">To</div>
                    <strong class="text-dark fs-6">${toLocValue}</strong>
                    ${toPalletValue ? `<div class="text-secondary small">Pallet ${toPalletValue}</div>` : ''}
                </div>
            </div>

            <!-- QUANTITY -->
            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border">
                <span class="text-secondary small text-uppercase fw-semibold">Quantity to Move</span>
                <span class="badge rounded-pill text-bg-primary px-3 py-2 fs-6">${qtyToMove} ${fromUm}</span>
            </div>

            ${docNo ? `
            <div class="text-center text-secondary small mt-2">
                Document No. <strong>${docNo}</strong>
            </div>` : ''}

            <div class="text-center text-muted small mt-3">
                <i class="bi bi-info-circle me-1"></i>
                Please review before proceeding.
            </div>

        </div>
        `,

    showCancelButton: true,

    confirmButtonText:
        '<i class="bi bi-check-circle-fill me-1"></i> Proceed',

    cancelButtonText:
        '<i class="bi bi-x-circle me-1"></i> Cancel',

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
    .addEventListener('click', function () {

        confirmRelocation();

    });

});













