<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/datatables.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/datatables.min.js"></script>
    <style>
        #table-container {
            padding: 20px;
        }
        
        #table>tbody>tr>td:nth-child(2) {
            text-align: left !important;
        }

        #table>thead>tr>th,
        #table>thead>tr>td,
        #table>tbody>tr>td:first-child {
            text-align: center !important;
        }
    </style>
</head>

<body>
    <div id="#table-container">
        <table id="table" class="table table-striped" style="width: 100%"> </table>
    </div>
    <script>
        (() => {
            const timer = {};
    
            function setChecked(id, button) {
                if (typeof timer[id] === 'number') {
                    clearTimeout(timer[id]);
                }
                const value = $(button).prop('checked');
                timer[id] = setTimeout(() => {
                    $.ajax({
                        url: '/items/checked',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({  id, value  }),
                        success: function (data) {
                            if(data['success'] !== true){
                                $(button).prop('checked', !value);
                            }
                        },
                        error: function (err) {
                            $(button).prop('checked', !value);
                        }
                    });
                }, 500);
            }

           $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/items/list',
                language: { url: 'dataTable.i18n.th.json' },
                columns: [{
                    name: 'id',
                    title: '#',
                    data: 'id'
                },
                {
                    name: 'title',
                    title: 'ชื่อรายการ',
                    data: 'title'
                },
                {
                    name: 'checked',
                    title: 'แสดงผล',
                    data: 'checked',
                    render: (data, type, row) => {
                        return $(`<span class="form-check form-switch"></span>`).append(
                            $(`<input class="form-check-input" type="checkbox" role="switch" ${data ? 'checked' : ''}>`).on('click', function (ev) {
                                setChecked(row['id'], this);
                            })
                        ).get(0);
                    }
                }
                ]
            });
        })();
    </script>
</body>

</html>