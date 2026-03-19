<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Trabajadores</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .table thead {
            border-radius: 15px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark fw-bold">Trabajadores</h2>
                <button class="btn btn-primary px-4" onclick="showCreateModal()">+ Nuevo</button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Proyecto</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="employeeList">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="employeeForm">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="modalTitle">Trabajador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="emp_id">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" id="full_name" class="form-control" required minlength="3">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cargo</label>
                            <input type="text" id="role" class="form-control" required minlength="3">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Proyecto</label>
                            <input type="text" id="project" class="form-control" required minlength="3">
                        </div>
                    </div>
                    <div class="modal-footer border-0" id="modalFooter">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $.ajaxSetup({
            cache: false
        });

        function loadTable() {
            $.get('/api/employees', function(data) {
                let html = '';
                if (data.length === 0) {
                    html = '<tr><td colspan="3" class="text-center text-muted py-4">No hay datos</td></tr>';
                }
                data.forEach(e => {
                    html += `<tr>
                    <td>${e.id}</td>
                    <td>${e.full_name}</td>
                    <td>${e.role}</td>
                    <td>${e.project}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-info" onclick="viewDetails(${e.id})">Ver</button>
                        <button class="btn btn-sm btn-outline-warning mx-1" onclick="editForm(${e.id})">Editar</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteEmp(${e.id})">Eliminar</button>
                    </td>
                </tr>`;
                });
                $('#employeeList').html(html);
            });
        }

        function showCreateModal() {
            $('#employeeForm')[0].reset();
            $('#emp_id').val('');
            $('#full_name').prop('disabled', false);
            $('#role').prop('disabled', false);
            $('#project').prop('disabled', false);
            $('#modalTitle').text('Registrar Trabajador');
            $('#modalFooter').html(`
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
        `);
            $('#employeeModal').modal('show');
        }

        function editForm(id) {
            $.get(`/api/employees/${id}`, function(data) {
                $('#emp_id').val(data.id);
                $('#full_name').val(data.full_name).prop('disabled', false);
                $('#role').val(data.role).prop('disabled', false);
                $('#project').val(data.project).prop('disabled', false);
                $('#modalTitle').text('Editar Registro #' + data.id);
                $('#modalFooter').html(`
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-warning">Actualizar</button>
            `);
                $('#employeeModal').modal('show');
            });
        }

        function viewDetails(id) {
            $.get(`/api/employees/${id}`, function(data) {
                $('#full_name').val(data.full_name).prop('disabled', true);
                $('#role').val(data.role).prop('disabled', true);
                $('#project').val(data.project).prop('disabled', true);
                $('#modalTitle').text('Detalles del Trabajador');
                $('#modalFooter').html(
                    '<button type="button" class="btn btn-light" data-bs-dismiss="modal">Regresar</button>');
                $('#employeeModal').modal('show');
            });
        }

        $('#employeeForm').submit(function(e) {
            e.preventDefault();
            const id = $('#emp_id').val();
            const type = id ? 'PUT' : 'POST';
            const url = id ? `/api/employees/${id}` : '/api/employees';

            $.ajax({
                url: url,
                method: type,
                data: {
                    full_name: $('#full_name').val(),
                    role: $('#role').val(),
                    project: $('#project').val(),
                },
                success: function() {
                    $('#employeeModal').modal('hide');
                    loadTable();
                },
                error: function() {
                    alert("Error al procesar la petición.");
                }
            });
        });

        function deleteEmp(id) {
            if (confirm('¿Desea eliminar a este trabajador?')) {
                $.ajax({
                    url: `/api/employees/${id}`,
                    method: 'DELETE',
                    success: loadTable
                });
            }
        }

        $(document).ready(function() {
            loadTable();
        });
    </script>

</body>

</html>
