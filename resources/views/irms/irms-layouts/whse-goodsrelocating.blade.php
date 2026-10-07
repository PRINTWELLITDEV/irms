@extends('irms/irms-partials.app')

@section('title', 'Warehouse Goods Relocating')

@section('content')

<main class="app-main">
    <div class="app-content-wrapper">
        {{-- PAGE HEADER --}}
        <div class="app-content-header">
            <div class="container-lg">
                <div class="row align-items-center">
                    <div class="col mb-3">
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <div>
                                <h1 class="d-inline-block mb-0">
                                    Warehouse Goods Relocating
                                </h1>
                                <p class="text-muted mb-0 mt-1">
                                    Move stock from one location to another in two quick steps.
                                </p>
                            </div>
                            @if(session('success'))
                                <div id="success-alert" class="alert alert-success py-2 px-3 mb-0 d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if($errors->any())
                                <div id="error-alert" class="alert alert-danger py-2 px-3 mb-0 d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    {{ $errors->first() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-lg">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-9">

        

                        <form id="relocationForm" method="POST" action="#">
                            @csrf

                            <div class="card shadow-sm border-0 relocate-card">

                                {{-- ================= SOURCE SECTION ================= --}}
                                <div class="card-header border-0 py-3 px-4" style="background-color: #fdd56f">
                                    <div class="d-flex align-items-center">
                                        <span class="relocate-icon-badge bg-warning text-dark me-3">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </span>
                                        <div>
                                            <h5 class="mb-0 fw-bold">From (Source)</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                    @if(auth()->user()->level == 1)
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <select name="rssite" id="from_rssite" class="form-select" required>
                                                        <option value="" selected>Choose a site...</option>
                                                        @foreach($sites as $site)
                                                            <option value="{{ $site->rssite }}"
                                                                {{ old('rssite') == $site->rssite ? 'selected' : '' }}>
                                                                {{ $site->rssite_desc }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="from_rssite">
                                                        <i class="bi bi-building text-warning me-1"></i>
                                                        Site <span class="text-danger">*</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="rssite" id="from_rssite" value="{{ auth()->user()->rssite }}">
                                    @endif

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="date" class="form-control" id="date" name="date"
                                                       value="{{ date('Y-m-d') }}" required>
                                                <label for="date">
                                                    <i class="bi bi-calendar-event text-warning me-1"></i>
                                                    Date <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-floating">
                                                <select name="rswhse" id="from_rswhse" class="form-select" required>
                                                    <option value="" selected>Choose warehouse...</option>
                                                    @foreach($warehouses as $warehouse)
                                                        @if(auth()->user()->level == 1 || $warehouse->rssite === auth()->user()->rssite)
                                                            <option value="{{ $warehouse->rswhse }}" data-site="{{ $warehouse->rssite }}">
                                                                {{ $warehouse->rswhse }} - {{ $warehouse->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <label for="from_rswhse">
                                                    <i class="bi bi-house text-warning me-1"></i>
                                                    Warehouse <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select name="rsbaynum" id="from_rsbaynum" class="form-select" required disabled>
                                                    <option value="">Select warehouse first</option>
                                                </select>
                                                <label for="from_rsbaynum">
                                                    <i class="bi bi-grid-3x3-gap text-warning me-1"></i>
                                                    Bay No. <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating position-relative">

    <input
        type="text"
        name="rsloc"
        id="from_rsloc"
        class="form-control"
        placeholder="Type location..."
        autocomplete="off"
        required
        disabled
    >

    <label for="from_rsloc">
        <i class="bi bi-geo-alt text-warning me-1"></i>
        Location <span class="text-danger">*</span>
    </label>

    <!-- Suggestions -->
    <div
        id="fromLocationSuggestions"
        class="list-group position-absolute w-100 shadow"
        style="z-index: 1050; display: none;"
    ></div>

</div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select name="rspallet" id="from_rspallet" class="form-select" required disabled>
                                                    <option value="">Select location first</option>
                                                </select>
                                                <label for="from_rspallet">
                                                    <i class="bi bi-box-seam text-warning me-1"></i>
                                                    Pallet No.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select name="jobco" id="from_jobco" class="form-select" required disabled>
                                                    <option value="">Select pallet first</option>
                                                </select>
                                                <label for="from_jobco">
                                                    <i class="bi bi-briefcase text-warning me-1"></i>
                                                    Job / CO <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ITEM INFO PANEL --}}
                                    <div id="itemInfoPanel" class="relocate-item-panel">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-info-circle text-warning me-2"></i>
                                            <h6 class="mb-0 fw-bold text-muted">Item on this pallet</h6>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control bg-white" id="from_item" name="item" readonly>
                                                    <label for="from_item"><i class="bi bi-box text-muted me-1"></i>Item Code</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control bg-white" id="from_qty" name="qty" readonly>
                                                    <label for="from_qty"><i class="bi bi-boxes text-muted me-1"></i>Quantity on Hand</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control bg-white" id="from_um" name="um" readonly>
                                                    <label for="from_um"><i class="bi bi-rulers text-muted me-1"></i>Unit of Measure</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control bg-white" id="from_desc" name="desc" readonly>
                                                    <label for="from_desc"><i class="bi bi-file-text text-muted me-1"></i>Description</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

         
                                {{-- ================= DESTINATION SECTION ================= --}}
                                <div class="card-header border-0 py-3 px-4" style="background-color: #39547c">
                                    <div class="d-flex align-items-center">
                                        <span class="relocate-icon-badge text-white me-3" style="background-color: #6c98c2">
                                            <i class="bi bi-box-arrow-in-down-right"></i>
                                        </span>
                                        <div>
                                            <h5 class="mb-0 text-light fw-bold">To (Destination)</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                    @if(auth()->user()->level == 1)
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <select id="to_rssite" class="form-select" required>
                                                        <option value="" selected>Choose a site...</option>
                                                        @foreach($sites as $site)
                                                            <option value="{{ $site->rssite }}">
                                                                {{ $site->rssite_desc }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="to_rssite">
                                                        <i class="bi bi-building text-primary me-1"></i>
                                                        Site <span class="text-danger">*</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" id="to_rssite" value="{{ auth()->user()->rssite }}">
                                    @endif

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select name="to_rswhse" id="to_rswhse" class="form-select" required>
                                                    <option value="" selected>Choose warehouse...</option>
                                                    @foreach($warehouses as $warehouse)
                                                        @if(auth()->user()->level == 1 || $warehouse->rssite === auth()->user()->rssite)
                                                            <option value="{{ $warehouse->rswhse }}" data-site="{{ $warehouse->rssite }}">
                                                                {{ $warehouse->rswhse }} - {{ $warehouse->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <label for="to_rswhse">
                                                    <i class="bi bi-house text-primary me-1"></i>
                                                    Warehouse <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select name="to_rsbaynum" id="to_rsbaynum" class="form-select" required disabled>
                                                    <option value="">Select warehouse first</option>
                                                </select>
                                                <label for="to_rsbaynum">
                                                    <i class="bi bi-grid-3x3-gap text-primary me-1"></i>
                                                    Bay No. <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select name="to_rsloc" id="to_rsloc" class="form-select" required disabled>
                                                    <option value="">Select bay first</option>
                                                </select>
                                                <label for="to_rsloc">
                                                    <i class="bi bi-geo-alt text-primary me-1"></i>
                                                    Location <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input
                                                    type="text"
                                                    name="to_rspallet"
                                                    id="to_rspallet"
                                                    class="form-control"
                                                    placeholder="Enter Pallet No..."
                                                    maxlength="10"
                                                    pattern="[A-Za-z0-9]+"
                                                    oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                                    title="Pallet No. must be alphanumeric and up to 10 characters."
                                                    required>
                                                <label for="to_rspallet">
                                                    <i class="bi bi-box-seam text-primary me-1"></i>
                                                    Pallet No. 
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-2">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="docno" name="docno"
                                                       placeholder="Document number">
                                                <label for="docno">
                                                    <i class="bi bi-file-earmark-text text-primary me-1"></i>
                                                    Document No. <small class="text-muted">(Optional)</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" id="qty_to_move" name="qty_to_move"
                                                       min="0.0001" step="any" required>
                                                <label for="qty_to_move">
                                                    <i class="bi bi-123 text-primary me-1"></i>
                                                    Quantity to Move <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-white border-0 p-4 pt-0">
                                    <button type="button" id="moveItemBtn" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm py-3">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Confirm Relocation
                                    </button>
                                    <p class="text-center text-muted small mt-2 mb-0">
                                        <i class="bi bi-shield-check me-1"></i>
                                        Please double check the destination before confirming.
                                    </p>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Step tracker */
    .relocate-steps { font-size: 0.95rem; }
    .relocate-step-dot {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        margin-right: 0.5rem;
    }
    .relocate-step-line {
        width: 48px;
        height: 2px;
        background: linear-gradient(to right, #ffc107, #0d6efd);
        margin: 0 1rem;
    }

    /* Card */
    .relocate-card { border-radius: 0.75rem; overflow: hidden; }
    .relocate-icon-badge {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* Item info panel */
    .relocate-item-panel {
        background-color: #FFF8D6;
        border-radius: 0.5rem;
        padding: 1rem 1rem 0.25rem;
        margin-top: 0.5rem;
    }

    /* Flow divider between From and To */
    .relocate-flow-divider {
        display: flex;
        align-items: center;
        padding: 0 2rem;
        background: #fff;
    }
    .relocate-flow-line {
        flex: 1;
        height: 2px;
        background: repeating-linear-gradient(to right, #dee2e6 0, #dee2e6 6px, transparent 6px, transparent 12px);
    }
    .relocate-flow-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 50%;
        background: #f1f3f5;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 0.75rem;
        font-size: 1.1rem;
    }

    /* Disabled selects look visibly inactive */
    .form-select:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    @media (max-width: 767.98px) {
        .relocate-steps { font-size: 0.85rem; }
        .relocate-step-line { width: 28px; margin: 0 0.5rem; }
    }
.guide-highlight {
    border: 2px solid #0d6efd !important;
    background-color: #f3f8ff !important;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12) !important;
    animation: guidePulse 2s ease-in-out infinite;
    transition: border-color 0.2s ease,
                background-color 0.2s ease,
                box-shadow 0.2s ease;
}

@keyframes guidePulse {
    0% {
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.10);
    }

    50% {
        box-shadow: 0 0 0 5px rgba(13, 110, 253, 0.20);
    }

    100% {
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.10);
    }
}

/* Highlight the label of the active field */
label.guide-label {
    color: #0d6efd;
    font-weight: 600;
    transition: color 0.2s ease;
}

/* Respect users who prefer less motion */
@media (prefers-reduced-motion: reduce) {
    .guide-highlight {
        animation: none;
    }
}

/* Green check on filled INPUT fields (e.g. Location) */
input.guide-done {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23198754' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M3 8.5l3.5 3.5L13 4'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right .75rem center !important;
    background-size: 18px 18px !important;
    padding-right: 2.5rem !important;
}

/* Green check on filled SELECT fields (placed left of the dropdown arrow) */
select.guide-done {
    background-image:
        url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23198754' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M3 8.5l3.5 3.5L13 4'/%3e%3c/svg%3e"),
        url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat, no-repeat !important;
    background-position: right 2.25rem center, right .75rem center !important;
    background-size: 18px 18px, 16px 12px !important;
    padding-right: 4rem !important;
}
/* Green border + soft shadow on filled fields */
input.guide-done,
select.guide-done {
    border: 1.5px solid #198754 !important;
    box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .18) !important;
    transition: border-color .2s ease, box-shadow .2s ease;
}
</style>

<script>
    window.goodsRelocatingRoutes = {
        bays: "{{ route('irms.goods-relocating.bays') }}",
        locations: "{{ route('irms.goods-relocating.locations') }}",
        pallets: "{{ route('irms.goods-relocating.pallets') }}",
        jobs: "{{ route('irms.goods-relocating.jobs') }}",
        item: "{{ route('irms.goods-relocating.item') }}",
        move: "{{ route('irms.goods-relocating.move') }}"
    };

    // UX-only helpers: enable dependent dropdowns as their prerequisite fills in,
    // and surface a live "quantity available" hint. These do not replace the
    // existing controller logic that populates each <select>'s <option> list —
    // they only toggle the disabled state and mirror the on-hand quantity.
    document.addEventListener('DOMContentLoaded', function () {
        const enableWhen = (triggerEl, targetEl) => {
            if (!triggerEl || !targetEl) return;
            triggerEl.addEventListener('change', function () {
                targetEl.disabled = !this.value;
                if (!this.value) targetEl.value = '';
            });
        };

        enableWhen(document.getElementById('from_rswhse'), document.getElementById('from_rsbaynum'));
        enableWhen(document.getElementById('from_rsbaynum'), document.getElementById('from_rsloc'));
        enableWhen(document.getElementById('from_rsloc'), document.getElementById('from_rspallet'));
        enableWhen(document.getElementById('from_rspallet'), document.getElementById('from_jobco'));
        enableWhen(document.getElementById('to_rswhse'), document.getElementById('to_rsbaynum'));
        enableWhen(document.getElementById('to_rsbaynum'), document.getElementById('to_rsloc'));

        const qtyOnHand = document.getElementById('from_qty');
        const qtyToMove = document.getElementById('qty_to_move');
        const qtyHint = document.getElementById('qtyAvailableHint');
        const um = document.getElementById('from_um');

        if (qtyOnHand && qtyToMove && qtyHint) {
            const refreshHint = () => {
                const val = qtyOnHand.value;
                if (val) {
                    qtyToMove.max = val;
                    qtyHint.textContent = 'Available: ' + val + (um && um.value ? ' ' + um.value : '') + ' at the source location.';
                } else {
                    qtyToMove.removeAttribute('max');
                    qtyHint.textContent = 'Select a source pallet to see quantity available.';
                }
            };
            // from_qty is populated by the existing controller script (e.g. on pallet
            // change via AJAX); observe it so the hint stays in sync without needing
            // to touch that logic.
            new MutationObserver(refreshHint).observe(qtyOnHand, { attributes: true, attributeFilter: ['value'] });
            qtyOnHand.addEventListener('input', refreshHint);
            qtyOnHand.addEventListener('change', refreshHint);
        }
    });
</script>

@endsection