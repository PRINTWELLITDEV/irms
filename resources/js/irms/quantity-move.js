$(document).ready(function () {
    // Functional baseline reset shortcut wrapper
    function resetField(selector, placeholderText) {
        $(selector)
            .html(`<option value="" selected disabled>${placeholderText}</option>`)
            .prop("disabled", true)
            .addClass("text-muted");
    }

    // 1. When Warehouse Changes -> Fetch and Populate Bay Locations
    $("#fromWhse").on("change", function () {
        let whse = $(this).val();
        let site = $("#QuantityMoveRssite").val();

        if (whse) {
            $.ajax({
                url: "/goodsdispatching/get-bays", // Match your web.php route URL
                type: "GET",
                data: { warehouse: whse, site: site },
                beforeSend: function() {
                    $("#formStatus").show();
                },
                success: function (data) {
                    let dropdown = $("#fromBayLoc");
                    dropdown.html('<option value="" selected disabled>Select Bay Location</option>');
                    
                    $.each(data, function (index, item) {
                        dropdown.append(`<option value="${item.rsbaynum}">${item.rsbaynum}</option>`);
                    });

                    dropdown.prop("disabled", false).removeClass("text-muted");
                },
                complete: function() {
                    $("#formStatus").hide();
                }
            });
            
            // Wipe remaining downstream chain steps
            resetField("#fromRsloc", "Select Rack Location");
            resetField("#fromPosition", "Select Position");
        } else {
            resetField("#fromBayLoc", "Select Bay Location");
            resetField("#fromRsloc", "Select Rack Location");
            resetField("#fromPosition", "Select Position");
        }
    });

    // 2. When Bay Location Changes -> Fetch and Populate Rack Locations
    $("#fromBayLoc").on("change", function () {
        let bay = $(this).val();
        let whse = $("#fromWhse").val();
        let site = $("#QuantityMoveRssite").val();

        if (bay) {
            $.ajax({
                url: "/goodsdispatching/get-racks",
                type: "GET",
                data: { warehouse: whse, site: site, bay: bay },
                beforeSend: function() { $("#formStatus").show(); },
                success: function (data) {
                    let dropdown = $("#fromRsloc");
                    dropdown.html('<option value="" selected disabled>Select Rack Location</option>');
                    
                    $.each(data, function (index, item) {
                        dropdown.append(`<option value="${item.rsloc}">${item.rsloc}</option>`);
                    });

                    dropdown.prop("disabled", false).removeClass("text-muted");
                },
                complete: function() { $("#formStatus").hide(); }
            });
            resetField("#fromPosition", "Select Position");
        } else {
            resetField("#fromRsloc", "Select Rack Location");
            resetField("#fromPosition", "Select Position");
        }
    });

    // 3. When Rack Location Changes -> Fetch and Populate Positions
    $("#fromRsloc").on("change", function () {
        let rack = $(this).val();
        let whse = $("#fromWhse").val();
        let site = $("#QuantityMoveRssite").val();

        if (rack) {
            $.ajax({
                url: "/goodsdispatching/get-positions",
                type: "GET",
                data: { warehouse: whse, site: site, rack_location: rack },
                beforeSend: function() { $("#formStatus").show(); },
                success: function (data) {
                    let dropdown = $("#fromPosition");
                    dropdown.html('<option value="" selected disabled>Select Position</option>');
                    
                    $.each(data, function (index, item) {
                        // Adapt field based on your column schema name (e.g., rsposition)
                        dropdown.append(`<option value="${item.rsposition}">${item.rsposition}</option>`);
                    });

                    dropdown.prop("disabled", false).removeClass("text-muted");
                },
                complete: function() { $("#formStatus").hide(); }
            });
        } else {
            resetField("#fromPosition", "Select Position");
        }
    });

    // Site selection trigger
    $("#QuantityMoveRssite").on("change", function () {
        let selectedSite = $(this).val();
        $("#fromWhse").val("").prop("disabled", false);

        $("#fromWhse option").each(function () {
            let optionSite = $(this).data("site");
            if (!optionSite) return;

            if (optionSite == selectedSite) {
                $(this).show().prop("disabled", false);
            } else {
                $(this).hide().prop("disabled", true);
            }
        });

        resetField("#fromBayLoc", "Select Bay Location");
        resetField("#fromRsloc", "Select Rack Location");
        resetField("#fromPosition", "Select Position");
    });
});