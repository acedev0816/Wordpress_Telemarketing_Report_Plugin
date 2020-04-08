if (typeof $ != 'undefined') {
    var cur_y = new Date().getFullYear();
    var cur_m = new Date().getMonth();
    //this is script for dashboard tables
    jQuery(document).ready(function() {

        //for team table
        function refresh() {

            var dpp = '',
                mpp = '';
            if ($('#datepicker').css('display') != 'none') {
                dpp = $('#datepicker').val();
            }
            if ($('#monthpicker').css('display') != 'none') {
                mpp = cur_y + "/" + cur_m;
            }

            var source = {
                datatype: "json",
                datafields: [
                    { name: 'No', type: 'string' },
                    { name: 'Name', type: 'string' },
                    { name: 'Team', type: 'string' },
                    { name: 'Dials', type: 'string' },
                    { name: 'Contacts', type: 'string' },
                    { name: 'Qt', type: 'string' },
                ],
                url: boardAjax.ajaxurl,
                cache: false,
            };
            var dataAdapter = new $.jqx.dataAdapter(source, {

                formatData: function(data) {

                    $.extend(data, {
                        mp: mpp,
                        dp: dpp,
                        action: "handle_individual"
                    });
                    return data;
                }

            });
            $("#individual_table").jqxGrid({
                source: dataAdapter,
                theme: 'classic',
                sortable: true,
                pageable: true,
                width: '100%',
                autoheight: true,
                columns: [
                    { text: 'No', datafield: 'No', width: '15%' },
                    { text: 'Name', datafield: 'Name', width: '15%' },
                    { text: 'Team', datafield: 'Team', width: '25%' },
                    { text: 'Dials', datafield: 'Dials', width: '15%' },
                    { text: 'Contacts', datafield: 'Contacts', width: '15%' },
                    { text: 'Quoted Transters', datafield: 'Qt', width: '15%' }
                ]
            });
            source = {
                datatype: "json",
                datafields: [
                    { name: 'No', type: 'string' },
                    { name: 'Team', type: 'string' },
                    { name: 'Dials', type: 'string' },
                    { name: 'Contacts', type: 'string' },
                    { name: 'Qt', type: 'string' },
                ],
                url: boardAjax.ajaxurl,
                cache: false
            };

            dataAdapter = new $.jqx.dataAdapter(source, {
                formatData: function(data) {
                    $.extend(data, {
                        mp: mpp,
                        dp: dpp,
                        action: "handle_team"
                    });
                    return data;
                }
            });

            $("#team_table").jqxGrid({
                source: dataAdapter,
                theme: 'classic',
                sortable: true,
                pageable: true,
                width: '100%',
                autoheight: true,
                columns: [
                    { text: 'No', datafield: 'No', width: '20%' },
                    { text: 'Team', datafield: 'Team', width: '30%' },
                    { text: 'Dials', datafield: 'Dials', width: '15%' },
                    { text: 'Contacts', datafield: 'Contacts', width: '15%' },
                    { text: 'Quoted Transters', datafield: 'Qt', width: '20%' }
                ]
            });
        }



        $("#datepicker").datepicker({
            dateFormat: 'yy/m/d',
        });


        $('#monthpicker').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'MM yy',
            onClose: function() {
                var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
            },
            beforeShow: function() {
                if ((selDate = $(this).val()).length > 0) {
                    iYear = selDate.substring(selDate.length - 4, selDate.length);
                    iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5),
                        $(this).datepicker('option', 'monthNames'));
                    $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
                    $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
                }
            },
            onChangeMonthYear: function(y, m, i) {
                cur_m = m;
                cur_y = y;
                refresh();
            }

        });
        $('#datepicker').datepicker('setDate', new Date());
        $('#monthpicker').datepicker('setDate', new Date());
        $('#period').on('change', function() {
            if (this.value == 1) {
                $('#datepicker').css("display", "block");
                $('#monthpicker').css("display", "none");
            }
            if (this.value == 2) {
                $('#datepicker').css("display", "none");
                $('#monthpicker').css("display", "block");
            }
        });
        //change of month and day
        $('#datepicker').on('change', function() {
            refresh();
        });

        $('#period').on('change', function() {
            refresh();
        });

        refresh();



    });
}