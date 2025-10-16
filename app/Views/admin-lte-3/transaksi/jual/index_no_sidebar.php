<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-01-18
 * Github: github.com/mikhaelfelian
 * Description: Sales Transaction Cashier Interface View
 * This file represents the View.
 * 
 * Required Controller Data:
 * - $transactions: Array of transaction records
 * - $customers: Array of customer records
 * - $cashiers: Array of cashier users from tbl_ion_user where ion_group = 5
 * - $search: Current search term
 * - $status: Current status filter
 * - $cashierFilter: Current cashier filter
 * - $dateFrom: Current date from filter
 * - $dateTo: Current date to filter
 * - $statusOptions: Array of status options
 * - $pager: Pagination object
 */
?>
<?= $this->extend(theme_path('main_no_sidebar')) ?>

<?= $this->section('content') ?>
<!-- CSRF Token -->
<?= csrf_field() ?>

<!-- Cashier Interface -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cash-register"></i> Kasir - Transaksi Penjualan
                </h3>
                <div class="card-tools">

                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari nota/pelanggan..."
                            value="<?= $search ?>">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" id="statusFilter">
                            <option value="">Semua Status</option>
                            <?php foreach ($statusOptions as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $status == $key ? 'selected' : '' ?>><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" id="cashierFilter">
                            <option value="">Semua Kasir</option>
                            <?php if (isset($cashiers) && !empty($cashiers)): ?>
                                <?php foreach ($cashiers as $cashier): ?>
                                    <option value="<?= $cashier->id ?>" <?= $cashierFilter == $cashier->id ? 'selected' : '' ?>>
                                        <?= esc($cashier->first_name . ' ' . $cashier->last_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="form-text text-muted">Filter berdasarkan kasir</small>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateFrom" value="<?= $dateFrom ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateTo" value="<?= $dateTo ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-secondary" id="searchBtn">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="resetBtn">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>No. Nota</th>
                                <th>Pelanggan</th>
                                <th>Kasir</th>
                                <th class="text-right">Total</th>
                                <th>Status</th>
                                <th>Status Bayar</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data transaksi</td>
                                </tr>
                            <?php else: ?>
                                <?php
                                $startNumber = ($currentPage - 1) * $perPage;
                                foreach ($transactions as $index => $row):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $startNumber + $index + 1 ?></td>
                                        <td>
                                            <strong><?= esc(isset($row->no_nota) ? $row->no_nota : 'Unknown') ?></strong><br/>
                                            <small class="text-muted"><?= tgl_indo6($row->created_at) ?></small><br/>
                                            <small class="text-muted"><?= esc($row->user_name ?? 'Umum') ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $customerName = 'Umum';
                                            if ($row->id_pelanggan) {
                                                foreach ($customers as $customer) {
                                                    if ($customer->id == $row->id_pelanggan) {
                                                        $customerName = isset($customer->nama) ? $customer->nama : 'Unknown';
                                                        break;
                                                    }
                                                }
                                            }
                                            echo esc($customerName);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $cashierName = 'Unknown';
                                            if (isset($row->user_id) && $row->user_id) {
                                                if (isset($cashiers) && is_array($cashiers)) {
                                                    foreach ($cashiers as $cashier) {
                                                        if ($cashier->id == $row->user_id) {
                                                            $firstName = $cashier->first_name ?? '';
                                                            $lastName = $cashier->last_name ?? '';
                                                            $cashierName = trim($firstName . ' ' . $lastName);
                                                            if (empty($cashierName)) {
                                                                $cashierName = $cashier->username ?? 'User';
                                                            }
                                                            break;
                                                        }
                                                    }
                                                }
                                                
                                                // If still unknown and we have user_id, show user ID
                                                if ($cashierName === 'Unknown') {
                                                    $cashierName = 'User ID: ' . $row->user_id;
                                                }
                                            } else {
                                                $cashierName = 'System';
                                            }
                                            echo esc($cashierName);
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <strong>Rp
                                                <?= number_format(isset($row->jml_gtotal) ? $row->jml_gtotal : 0, 0, ',', '.') ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                            $statusBadges = [
                                                '0' => '<span class="badge badge-secondary">Draft</span>',
                                                '1' => '<span class="badge badge-success">Selesai</span>',
                                                '2' => '<span class="badge badge-danger">Batal</span>',
                                                '3' => '<span class="badge badge-warning">Retur</span>',
                                                '4' => '<span class="badge badge-info">Pending</span>'
                                            ];
                                            echo $statusBadges[isset($row->status) ? $row->status : '0'] ?? '<span class="badge badge-secondary">Unknown</span>';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $paymentStatus = [
                                                '0' => '<span class="badge badge-warning">Belum Lunas</span>',
                                                '1' => '<span class="badge badge-success">Lunas</span>',
                                                '2' => '<span class="badge badge-danger">Kurang</span>'
                                            ];
                                            echo $paymentStatus[isset($row->status_bayar) ? $row->status_bayar : '0'] ?? '<span class="badge badge-secondary">Unknown</span>';
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm"
                                                    onclick="viewTransaction(<?= $row->id ?>)" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if (isset($row->status) && $row->status == '0'): ?>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="editTransaction(<?= $row->id ?>)" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if (isset($row->status) && $row->status == '1'): ?>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="showPrintOptions(<?= $row->id ?>)" title="Cetak">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    <?= $pager->links('transjual', 'adminlte_pagination') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Sidebar -->
    <div class="col-md-4">
        <div class="card shadow-sm rounded-0 border-0 mb-4">
            <div class="card-header bg-white border-bottom-0 rounded-0 pb-2 pt-3">
                <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size:1.25rem;">
                    <i class="fas fa-bolt text-warning mr-2"></i> Aksi Cepat
                </h3>
            </div>
            <div class="card-body p-3 rounded-0">
                <div class="d-flex flex-column gap-3">
                    <a href="<?= base_url('transaksi/jual/cashier') ?>"
                        class="btn btn-success btn-lg rounded-0 py-3 mb-2 shadow-sm text-left d-flex align-items-center">
                        <i class="fas fa-cash-register fa-lg mr-3"></i>
                        <span class="font-weight-bold" style="font-size:1.1rem;">Buka Kasir</span>
                    </a>
                    <a href="<?= base_url('transaksi/shift') ?>"
                        class="btn btn-primary btn-lg rounded-0 py-3 mb-2 shadow-sm text-left d-flex align-items-center">
                        <i class="fas fa-user-clock fa-lg mr-3"></i>
                        <span class="font-weight-bold" style="font-size:1.1rem;">Manajemen Shift</span>
                    </a>
                    <a href="<?= base_url('transaksi/petty') ?>"
                        class="btn btn-warning btn-lg rounded-0 py-3 mb-2 shadow-sm text-left d-flex align-items-center">
                        <i class="fas fa-wallet fa-lg mr-3"></i>
                        <span class="font-weight-bold" style="font-size:1.1rem;">Kas Kecil</span>
                    </a>
                    <a href="<?= base_url('transaksi/retur/jual/refund') ?>"
                        class="btn btn-danger btn-lg rounded-0 py-3 mb-2 shadow-sm text-left d-flex align-items-center">
                        <i class="fas fa-undo-alt fa-lg mr-3"></i>
                        <span class="font-weight-bold" style="font-size:1.1rem;">Retur Penjualan</span>
                    </a>
                    <a href="<?= base_url('/transaksi/refund') ?>"
                        class="btn btn-info btn-lg rounded-0 py-3 mb-2 shadow-sm text-left d-flex align-items-center">
                        <i class="fas fa-money-bill-wave fa-lg mr-3"></i>
                        <span class="font-weight-bold" style="font-size:1.1rem;">Refund</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock"></i> Transaksi Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php
                    $recentTransactions = array_slice($transactions, 0, 5);
                    foreach ($recentTransactions as $row):
                        ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?= esc(isset($row->no_nota) ? $row->no_nota : 'Unknown') ?></h6>
                                <small><?= isset($row->created_at) ? date('H:i', strtotime($row->created_at)) : '-' ?></small>
                            </div>
                            <p class="mb-1">Rp
                                <?= number_format(isset($row->jml_gtotal) ? $row->jml_gtotal : 0, 0, ',', '.') ?></p>
                            <small>
                                <?php
                                $statusBadges = [
                                    '0' => '<span class="badge badge-secondary">Draft</span>',
                                    '1' => '<span class="badge badge-success">Selesai</span>',
                                    '2' => '<span class="badge badge-danger">Batal</span>',
                                    '3' => '<span class="badge badge-warning">Retur</span>',
                                    '4' => '<span class="badge badge-info">Pending</span>'
                                ];
                                echo $statusBadges[isset($row->status) ? $row->status : '0'] ?? '';
                                ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Transaction Modal -->
<div class="modal fade" id="newTransactionModal" tabindex="-1" role="dialog" aria-labelledby="newTransactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTransactionModalLabel">
                    <i class="fas fa-plus"></i> Transaksi Baru
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newTransactionForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_nota">No. Nota</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="no_nota" name="no_nota" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="generateNotaNumber()">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_pelanggan">Pelanggan</label>
                                <select class="form-control select2" id="id_pelanggan" name="id_pelanggan">
                                    <option value="">Pilih Pelanggan</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer->id ?>">
                                            <?= esc(isset($customer->nama) ? $customer->nama : 'Unknown') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_sales">Sales</label>
                                <select class="form-control select2" id="id_sales" name="id_sales">
                                    <option value="">Pilih Sales</option>
                                    <?php foreach ($sales as $sale): ?>
                                        <option value="<?= $sale->id ?>">
                                            <?= esc(isset($sale->nama) ? $sale->nama : 'Unknown') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_gudang">Gudang</label>
                                <select class="form-control select2" id="id_gudang" name="id_gudang">
                                    <option value="">Pilih Gudang</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse->id ?>">
                                            <?= esc(isset($warehouse->gudang) ? $warehouse->gudang : 'Unknown') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="createTransaction()">
                    <i class="fas fa-save"></i> Buat Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div class="modal fade" id="transactionDetailModal" tabindex="-1" role="dialog"
    aria-labelledby="transactionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionDetailModalLabel">
                    <i class="fas fa-eye"></i> Detail Transaksi
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="transactionDetailContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="printBtn" style="display: none;">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function () {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Generate nota number on page load
        generateNotaNumber();

        // Search functionality
        $('#searchBtn').on('click', function () {
            performSearch();
        });

        // Reset search
        $('#resetBtn').on('click', function () {
            $('#searchInput').val('');
            $('#statusFilter').val('');
            $('#cashierFilter').val('');
            $('#dateFrom').val('');
            $('#dateTo').val('');
            performSearch();
        });

        // Enter key on search input
        $('#searchInput').on('keypress', function (e) {
            if (e.which == 13) {
                performSearch();
            }
        });
    });

    function performSearch() {
        const search = $('#searchInput').val();
        const status = $('#statusFilter').val();
        const cashier = $('#cashierFilter').val();
        const dateFrom = $('#dateFrom').val();
        const dateTo = $('#dateTo').val();

        let url = '<?= base_url('transaksi/jual') ?>?';
        const params = new URLSearchParams();

        if (search) params.append('search', search);
        if (status) params.append('status', status);
        if (cashier) params.append('cashier', cashier);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);

        window.location.href = url + params.toString();
    }

    function generateNotaNumber() {
        $.ajax({
            url: '<?= base_url('transaksi/jual/generate-nota') ?>',
            type: 'GET',
            success: function (response) {
                if (response.success) {
                    $('#no_nota').val(response.nota_number);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error generating nota number:', error);
                if (xhr.status === 401) {
                    toastr.error('Sesi Anda telah berakhir. Silakan login ulang.');
                    setTimeout(function () {
                        window.location.href = '<?= base_url('auth/login') ?>';
                    }, 2000);
                }
            }
        });
    }

    function viewTransaction(id) {
        $.ajax({
            url: '<?= base_url('transaksi/jual/get-details') ?>/' + id,
            type: 'GET',
            success: function (response) {
                if (response.success) {
                    $('#transactionDetailContent').html(generateTransactionDetailHTML(response));
                    $('#transactionDetailModal').modal('show');
                } else {
                    toastr.error('Gagal memuat detail transaksi');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error loading transaction details:', error);
                if (xhr.status === 401) {
                    toastr.error('Sesi Anda telah berakhir. Silakan login ulang.');
                    setTimeout(function () {
                        window.location.href = '<?= base_url('auth/login') ?>';
                    }, 2000);
                }
            }
        });
    }

    function generateTransactionDetailHTML(data) {
        const transaction = data.transaction;
        const details = data.details;
        const platforms = data.platforms;

        let html = `
        <div class="row">
            <div class="col-md-6">
                <h6>Informasi Transaksi</h6>
                <table class="table table-sm">
                    <tr><td>No. Nota</td><td>: <strong>${transaction.no_nota}</strong></td></tr>
                    <tr><td>Tanggal</td><td>: ${new Date(transaction.created_at).toLocaleString('id-ID')}</td></tr>
                    <tr><td>Status</td><td>: ${getStatusBadge(transaction.status)}</td></tr>
                    <tr><td>Status Bayar</td><td>: ${getPaymentStatusBadge(transaction.status_bayar)}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Total</h6>
                <table class="table table-sm">
                    <tr><td>Subtotal</td><td class="text-right">Rp ${numberFormat(transaction.jml_subtotal)}</td></tr>
                    <tr><td>Diskon</td><td class="text-right">Rp ${numberFormat(transaction.jml_diskon)}</td></tr>
                    <tr><td>PPN</td><td class="text-right">Rp ${numberFormat(transaction.jml_ppn)}</td></tr>
                    <tr><td><strong>Grand Total</strong></td><td class="text-right"><strong>Rp ${numberFormat(transaction.jml_gtotal)}</strong></td></tr>
                </table>
            </div>
        </div>
        <hr>
        <h6>Detail Item</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>`;

        details.forEach((detail, index) => {
            html += `
            <tr>
                <td>${index + 1}</td>
                <td>${detail.produk || detail.nama_item}</td>
                <td>${detail.jml} ${detail.satuan || detail.nama_satuan}</td>
                <td class="text-right">Rp ${numberFormat(detail.harga)}</td>
                <td class="text-right">Rp ${numberFormat(detail.subtotal)}</td>
            </tr>`;
        });

        html += `
                </tbody>
            </table>
        </div>`;

        if (platforms && platforms.length > 0) {
            html += `
        <hr>
        <h6>Platform Pembayaran</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Platform</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>`;

            platforms.forEach(platform => {
                html += `
                <tr>
                    <td>${platform.platform}</td>
                    <td class="text-right">Rp ${numberFormat(platform.nominal)}</td>
                    <td>${platform.keterangan || '-'}</td>
                </tr>`;
            });

            html += `
                </tbody>
            </table>
        </div>`;
        }

        return html;
    }

    function getStatusBadge(status) {
        const badges = {
            '0': '<span class="badge badge-secondary">Draft</span>',
            '1': '<span class="badge badge-success">Selesai</span>',
            '2': '<span class="badge badge-danger">Batal</span>',
            '3': '<span class="badge badge-warning">Retur</span>',
            '4': '<span class="badge badge-info">Pending</span>'
        };
        return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
    }

    function getPaymentStatusBadge(status) {
        const badges = {
            '0': '<span class="badge badge-warning">Belum Lunas</span>',
            '1': '<span class="badge badge-success">Lunas</span>',
            '2': '<span class="badge badge-danger">Kurang</span>'
        };
        return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
    }

    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID').format(number || 0);
    }

    function createTransaction() {
        const formData = {
            no_nota: $('#no_nota').val(),
            id_pelanggan: $('#id_pelanggan').val(),
            id_sales: $('#id_sales').val(),
            id_gudang: $('#id_gudang').val()
        };

        // Basic validation
        if (!formData.no_nota) {
            toastr.error('No. Nota harus diisi');
            return;
        }

        // Here you would typically submit the form to create a new transaction
        // For now, we'll just close the modal and show a success message
        $('#newTransactionModal').modal('hide');
        toastr.success('Transaksi baru berhasil dibuat');

        // Reload the page to show the new transaction
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    function editTransaction(id) {
        // Redirect to edit page or open edit modal
        window.location.href = '<?= base_url('transaksi/jual/edit') ?>/' + id;
    }

    // Global variables
    let currentTransactionData = null;

    function showPrintOptions(id) {
        // Store transaction ID for printing
        $('#printTransactionId').val(id);
        
        // Fetch transaction data and store it globally
        $.ajax({
            url: '<?= base_url('transaksi/jual/get-transaction-for-print') ?>/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    currentTransactionData = response.transaction;
                    // Open print options modal
                    $('#printOptionsModal').modal('show');
                } else {
                    toastr.error('Gagal memuat data transaksi: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Gagal memuat data transaksi: ' + error);
            }
        });
    }

    function printReceipt(type, transactionData) {
        // If no transaction data provided, fetch it
        if (!transactionData) {
            const transactionId = $('#printTransactionId').val();
            if (!transactionId) {
                toastr.error('Transaksi tidak ditemukan.');
                return;
            }

            // Fetch transaction data first
            $.ajax({
                url: '<?= base_url('transaksi/jual/get-transaction-for-print') ?>/' + transactionId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.transaction;
                        
                        if (type === 'pdf') {
                            printToPDF(data);
                        } else if (type === 'printer') {
                            printToPrinter(data);
                        }
                        
                        $('#printOptionsModal').modal('hide'); // Close print options modal
                    } else {
                        toastr.error('Gagal memuat data transaksi: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Gagal memuat data transaksi: ' + error);
                }
            });
        } else {
            // Use provided transaction data
            if (type === 'pdf') {
                printToPDF(transactionData);
            } else if (type === 'printer') {
                printToPrinter(transactionData);
            }
        }
    }

    function printToPDF(transactionData) {
        // Create URL with query parameters
        const url = '<?= base_url('transaksi/jual/print-receipt-view') ?>';
        const params = new URLSearchParams();
        
        // Add transaction data
        params.append('transactionData', JSON.stringify(transactionData));
        params.append('printType', 'pdf');
        params.append('showButtons', 'true');
        
        // Open in new window
        const printWindow = window.open(url + '?' + params.toString(), '_blank', 'width=800,height=600');
        
        if (!printWindow) {
            toastr.error('Pop-up blocked. Please allow pop-ups for this site.');
        }
    }

    function printToPrinter(transactionData) {
        // Create URL with query parameters
        const url = '<?= base_url('transaksi/jual/print-receipt-view') ?>';
        const params = new URLSearchParams();
        
        // Add transaction data
        params.append('transactionData', JSON.stringify(transactionData));
        params.append('printType', 'printer');
        params.append('showButtons', 'true');
        
        // Open in new window
        const printWindow = window.open(url + '?' + params.toString(), '_blank', 'width=400,height=600');
        
        if (!printWindow) {
            toastr.error('Pop-up blocked. Please allow pop-ups for this site.');
        }
    }

    function openSO() {
        // Redirect to sales order creation page
        window.location.href = '<?= base_url('transaksi/jual/create') ?>';
    }

    function openCashier() {
        // Redirect to cashier page
        window.location.href = '<?= base_url('transaksi/jual/cashier') ?>';
    }

    function generateReceiptHTML(transactionData) {
        const { no_nota, customer_name, customer_type, items, subtotal, discount, voucher, ppn, total, payment_methods, date, outlet } = transactionData;
        
        let itemsHTML = '';
        if (items && items.length > 0) {
            items.forEach(item => {
                itemsHTML += `
                    <div class="item">
                        <div>${item.name}</div>
                        <div>${item.quantity} x ${formatCurrency(item.price)} = ${formatCurrency(item.total)}</div>
                    </div>
                `;
            });
        } else {
            itemsHTML = '<div class="item">No items available</div>';
        }
        
        let paymentHTML = '';
        if (payment_methods && payment_methods.length > 0) {
            payment_methods.forEach(pm => {
                const methodName = pm.type === '1' ? 'Tunai' : pm.type === '2' ? 'Non Tunai' : 'Piutang';
                paymentHTML += `<div>${methodName}: ${formatCurrency(pm.amount)}</div>`;
            });
        }
        
        return `
            <div class="receipt">
                <div class="header">
                    <h3>KOPMENSA</h3>
                    <div>${outlet || 'OUTLET'}</div>
                    <div>${date || new Date().toLocaleString('id-ID')}</div>
                    <div>No: ${no_nota || 'DRAFT'}</div>
                </div>
                
                <div class="divider"></div>
                
                <div class="customer">
                    <div>Customer: ${customer_name || 'UMUM'}</div>
                    <div>Type: ${customer_type || 'UMUM'}</div>
                </div>
                
                <div class="divider"></div>
                
                <div class="items">
                    ${itemsHTML}
                </div>
                
                <div class="divider"></div>
                
                <div class="summary">
                    <div>Subtotal: ${formatCurrency(subtotal || 0)}</div>
                    ${discount > 0 ? `<div>Diskon: ${discount}%</div>` : ''}
                    ${voucher ? `<div>Voucher: ${voucher}</div>` : ''}
                    <div>PPN (${ppn || 11}%): ${formatCurrency((subtotal || 0) * (ppn || 11) / 100)}</div>
                    <div class="total">TOTAL: ${formatCurrency(total || 0)}</div>
                </div>
                
                ${paymentHTML ? `
                    <div class="divider"></div>
                    <div class="payment">
                        <div><strong>Pembayaran:</strong></div>
                        ${paymentHTML}
                    </div>
                ` : ''}
                
                <div class="divider"></div>
                
                <div class="footer">
                    <div>Terima kasih atas kunjungan Anda</div>
                    <div>Barang yang sudah dibeli tidak dapat dikembalikan</div>
                    <div>Powered by Kopmensa System</div>
                </div>
            </div>
        `;
    }

    function formatCurrency(amount) {
        return `Rp ${numberFormat(amount)}`;
    }

    function viewReports() {
        // Redirect to reports page
        window.location.href = '<?= base_url('transaksi/jual/reports') ?>';
    }

    function viewReturns() {
        // Redirect to returns page
        window.location.href = '<?= base_url('transaksi/jual/returns') ?>';
    }
</script>

<!-- Print Options Modal -->
<div class="modal fade" id="printOptionsModal" tabindex="-1" role="dialog" aria-labelledby="printOptionsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="printOptionsModalLabel">Pilih Metode Cetak</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                <h6>Cetak ke PDF</h6>
                <p class="text-muted">Simpan sebagai file PDF atau cetak via browser</p>
                <button type="button" class="btn btn-danger btn-block" onclick="printReceipt('pdf', currentTransactionData)">
                  <i class="fas fa-file-pdf"></i> PDF
                </button>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-print fa-3x text-success mb-3"></i>
                <h6>Cetak ke Printer</h6>
                <p class="text-muted">Cetak langsung ke dot matrix printer</p>
                <button type="button" class="btn btn-success btn-block" onclick="printReceipt('printer', currentTransactionData)">
                  <i class="fas fa-print"></i> Printer
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- Hidden field to store transaction ID -->
        <input type="hidden" id="printTransactionId" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>