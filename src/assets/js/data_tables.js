
function dataTablesGrid(selector, var_grid_name, url, columns, more_data, initComplete, scrollX, scrollY, scrollCollapse, orderBy, orderByDesc, row_select) {
    scrollX = scrollX || false;
    scrollY = scrollY || false;
    scrollCollapse = scrollCollapse || false;
    orderBy = orderBy || 0;
    orderByDesc = orderByDesc || "desc";
    more_data = more_data || {};
    row_select = row_select || false;
    var columnDefs = [];
    window[var_grid_name + '_rows_selected'] = [];
    if (row_select) {
        checkbox_column = {
            title: '<input name="select_all" value="1" type="checkbox"/>',
            searchable: false,
            orderable: false,
            width: '1%',
            className: 'dt-body-center',
            render: function (data, type, full, meta) {
                return '<input type="checkbox">';
            }
        };
        columns.unshift(checkbox_column);

    }

    var dataTableOptionObj =
        {
            initComplete: function () {
                if (initComplete == true) {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select class="filter-select" data-placeholder="Filter"><option value=""></option></select>')
                            .appendTo($(column.footer()).not(':last-child').empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            },
            "searching": false,
            serverSide: false,
            ajax: {
                url: url,
                type: 'POST',
                data: more_data
            },
            columns: columns,
            scrollX: scrollX,
            scrollY: scrollY,
            scrollCollapse: scrollCollapse,
            order: [[ orderBy, orderByDesc ]],
            rowCallback: function (row, data, dataIndex) {
                if (row_select) {
                    var rowId = data;
                    // If row ID is in the list of selected row IDs
                    if (func_search_in_obj('id', data['id'], window[var_grid_name + '_rows_selected'])) {
                        $(row).find('input[type="checkbox"]').prop('checked', true);
                        $(row).addClass('selected');
                    }
                }
            },
            destroy: true
        };

    if(!scrollY)
    {
        delete  dataTableOptionObj.scrollY;
        delete  dataTableOptionObj.scrollCollapse;
    }

    window[var_grid_name] = $(selector).DataTable(dataTableOptionObj);

    if (row_select) {
        $(selector).on('click', 'input[type="checkbox"]', function (e) {
            var $row = $(this).closest('tr');
            // Get row data
            var data = window[var_grid_name].row($row).data();
            //console.log(data);
            // Get row ID
            //var rowId = data['id'];
            var rowId = data;
            // Determine whether row ID is in the list of selected row IDs
            var index = $.inArray(rowId, window[var_grid_name + '_rows_selected']);
            // If checkbox is checked and row ID is not in list of selected row IDs
            if (this.checked && index === -1) {
                window[var_grid_name + '_rows_selected'].push(rowId);
                // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1) {
                window[var_grid_name + '_rows_selected'].splice(index, 1);
            }
            if (this.checked) {
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(window[var_grid_name]);
            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle click on table cells with checkboxes
        $(selector).on('click', 'tbody td, thead th:first-child', function (e) {
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', window[var_grid_name].table().container()).on('click', function (e) {
            if (this.checked) {
                $(selector + ' tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $(selector + ' tbody input[type="checkbox"]:checked').trigger('click');
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        window[var_grid_name].on('draw', function () {
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(window[var_grid_name]);
        });
    }
}

function updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[type="checkbox"]', $table);
    var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

    // If none of the checkboxes are checked
    if ($chkbox_checked.length === 0) {
        chkbox_select_all.checked = false;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
        // If all of the checkboxes are checked
    } else if ($chkbox_checked.length === $chkbox_all.length) {
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
        // If some of the checkboxes are checked
    } else {
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = true;
        }
    }
}

//dataTablesGrid('#SysProcessGridData', 'SysProcessGridData', getSysProcessRoute, sys_process_grid_columns);