<?php
/**
 * Created by:
 * Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * 2025-07-12
 * 
 * Purchase Transaction Edit View
 */
?>
<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <?= form_open("transaksi/beli/update/$transaksi->id", ['id' => 'form-transaksi']) ?>
            <div class="card rounded-0">
                <div class="card-body">
                    <!-- PO Selection -->
                    <div class="form-group">
                        <label for="id_po">Kode PO</label>
                        <select name="id_po" id="id_po" class="form-control select2 rounded-0">
                            <option value="">Pilih PO</option>
                            <?php foreach ($po_list as $po): ?>
                                <option value="<?= $po->id ?>" 
                                    data-supplier="<?= $po->id_supplier ?>"
                                    data-no-po="<?= $po->no_nota ?>"
                                    <?= old('id_po', $transaksi->id_po) == $po->id ? 'selected' : '' ?>>
                                    <?= esc($po->no_nota). ' - '.esc($po->supplier) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Supplier -->
                            <div class="form-group">
                                <label for="id_supplier">Supplier <span class="text-danger">*</span></label>
                                <select name="id_supplier" id="id_supplier" class="form-control select2 rounded-0" required>
                                    <option value="">Pilih Supplier</option>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?= $supplier->id ?>" 
                                            <?= old('id_supplier', $transaksi->id_supplier) == $supplier->id ? 'selected' : '' ?>>
                                            <?= esc($supplier->nama) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <!-- Tanggal Faktur -->
                            <div class="form-group">
                                <label for="tgl_masuk">Tanggal Faktur <span class="text-danger">*</span></label>
                                <?= form_input([
                                    'type' => 'date',
                                    'name' => 'tgl_masuk',
                                    'id' => 'tgl_masuk',
                                    'class' => 'form-control rounded-0',
                                    'value' => old('tgl_masuk', $transaksi->tgl_masuk),
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- No PO -->
                            <div class="form-group">
                                <label for="no_po">No. PO</label>
                                <?= form_input([
                                    'type' => 'text',
                                    'name' => 'no_po',
                                    'id' => 'no_po',
                                    'class' => 'form-control rounded-0',
                                    'value' => old('no_po', $transaksi->no_po),
                                    'readonly' => true
                                ]) ?>
                            </div>

                            <!-- Tanggal Tempo -->
                            <div class="form-group">
                                <label for="tgl_keluar">Tanggal Tempo</label>
                                <?= form_input([
                                    'type' => 'date',
                                    'name' => 'tgl_keluar',
                                    'id' => 'tgl_keluar',
                                    'class' => 'form-control rounded-0',
                                    'value' => old('tgl_keluar', $transaksi->tgl_keluar)
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <!-- No Faktur -->
                    <div class="form-group">
                        <label for="no_nota">No. Faktur <span class="text-danger">*</span></label>
                        <?= form_input([
                            'type' => 'text',
                            'name' => 'no_nota',
                            'id' => 'no_nota',
                            'class' => 'form-control rounded-0',
                            'value' => old('no_nota', $transaksi->no_nota),
                            'required' => true
                        ]) ?>
                    </div>

                    <!-- Status PPN -->
                    <div class="form-group">
                        <label>Status PPN <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_ppn" id="ppn_non" value="0"
                                    <?= old('status_ppn', $transaksi->status_ppn) == '0' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="ppn_non">Non PPN</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_ppn" id="ppn_tambah" value="1"
                                    <?= old('status_ppn', $transaksi->status_ppn) == '1' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="ppn_tambah">Tambah PPN</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_ppn" id="ppn_include" value="2"
                                    <?= old('status_ppn', $transaksi->status_ppn) == '2' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="ppn_include">Include PPN</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="<?= base_url('transaksi/beli') ?>" class="btn btn-default float-left rounded-0">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary rounded-0">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        <?= form_close() ?>
    </div>
    <div class="col-md-6">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shopping-cart mr-1"></i> Item Pembelian
                </h3>
            </div>
            <div class="card-body">
                <?= form_open(base_url("transaksi/beli/cart_add/{$transaksi->id}"), ['id' => 'form-item', 'method' => 'post']) ?>
                    <!-- Item Selection -->
                    <div class="form-group">
                        <label for="id_item">Item <span class="text-danger">*</span></label>
                        <select name="id_item" id="id_item" class="form-control select2 rounded-0" required>
                            <option value="">Pilih Item</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Jumlah -->
                            <div class="form-group">
                                <label for="jml">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" name="jml" id="jml" class="form-control rounded-0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Satuan -->
                            <div class="form-group">
                                <label for="id_satuan">Satuan <span class="text-danger">*</span></label>
                                <select name="id_satuan" id="id_satuan" class="form-control select2 rounded-0" required>
                                    <option value="">Pilih Satuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Harga -->
                            <div class="form-group">
                                <label for="harga">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-0">Rp</span>
                                    </div>
                                    <input type="text" name="harga" id="harga" class="form-control rounded-0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Potongan -->
                            <div class="form-group">
                                <label for="potongan">Potongan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-0">Rp</span>
                                    </div>
                                    <input type="text" name="potongan" id="potongan" class="form-control rounded-0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Diskon -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="disk1">Diskon 1 (%)</label>
                                <input type="number" name="disk1" id="disk1" class="form-control rounded-0" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="disk2">Diskon 2 (%)</label>
                                <input type="number" name="disk2" id="disk2" class="form-control rounded-0" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="disk3">Diskon 3 (%)</label>
                                <input type="number" name="disk3" id="disk3" class="form-control rounded-0" min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary rounded-0">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Edit Transaksi Pembelian</h3>
                <span class="badge badge-warning float-right">Draft</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Item</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Potongan</th>
                            <th>Subtotal</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transaksi->items)): ?>
                            <?php foreach ($transaksi->items as $i => $item): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <?= esc($item->kode) ?><br>
                                        <?= esc($item->item) ?><br>
                                    </td>
                                    <td><?= (float) $item->jml?> <?= esc($item->satuan) ?></td>
                                    <td><?= format_angka($item->harga) ?></td>
                                    <td><?= format_angka($item->disk1 + $item->disk2 + $item->disk3) ?>%</td>
                                    <td><?= format_angka($item->potongan) ?></td>
                                    <td><?= format_angka($item->subtotal) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm rounded-0 btn-edit"
                                                data-id="<?= $item->id ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm rounded-0 btn-delete"
                                                data-id="<?= $item->id ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada item</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Subtotal</strong></td>
                            <td colspan="2"><?= format_angka($transaksi->jml_subtotal ?? 0) ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>DPP</strong></td>
                            <td colspan="2"><?= format_angka($transaksi->jml_dpp ?? 0) ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>PPN (11%)</strong></td>
                            <td colspan="2"><?= format_angka($transaksi->jml_ppn ?? 0) ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Grand Total</strong></td>
                            <td colspan="2"><?= format_angka($transaksi->jml_total ?? 0) ?></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('transaksi/beli/proses/' . $transaksi->id) ?>" 
                   class="btn btn-success rounded-0 float-right">
                    <i class="fas fa-check mr-1"></i> Proses
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>
<script>
$(document).ready(function() {
    // Initialize autoNumeric for currency fields
    $("input[id=harga]").autoNumeric({aSep: '.', aDec: ',', aPad: false});
    $("input[id=potongan]").autoNumeric({aSep: '.', aDec: ',', aPad: false});
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Handle PO selection
    $('#id_po').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const supplierId = selectedOption.data('supplier');
        const noPo = selectedOption.data('no-po');
        
        // Set supplier dropdown value
        $('#id_supplier').val(supplierId).trigger('change');
        
        // Set No PO field value
        $('#no_po').val(noPo);
    });

    // Load items for the current transaction
    loadItems();

    // Function to load items
    function loadItems() {
        const transactionId = <?= $transaksi->id ?>;
        
        $.ajax({
            url: '<?= base_url('transaksi/beli/get-items/') ?>' + transactionId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    populateItemDropdown(response.items);
                } else {
                    console.error('Error loading items:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax error:', error);
            }
        });
    }

    // Function to populate item dropdown
    function populateItemDropdown(items) {
        const itemSelect = $('#id_item');
        itemSelect.empty();
        itemSelect.append('<option value="">Pilih Item</option>');
        
        items.forEach(function(item) {
            itemSelect.append(`<option value="${item.id}" 
                data-satuan="${item.satuan}" 
                data-id-satuan="${item.id_satuan}" 
                data-jml-satuan="${item.jml_satuan}">
                ${item.kode} - ${item.item}
            </option>`);
        });
    }

    // Handle form submission for adding items
    $('#form-item').on('submit', function(e) {
        console.log('Form submission intercepted by JavaScript');
        e.preventDefault();
        
        const formData = $(this).serialize();
        const actionUrl = $(this).attr('action');
        
        console.log('Form action URL:', actionUrl);
        console.log('Form data:', formData);
        
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                console.log('Sending POST request to:', actionUrl);
                // Disable submit button and show loading state
                $('#form-item button[type="submit"]').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin mr-1"></i> Menambahkan...');
            },
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    // Show success message
                    toastr.success(response.message);
                    
                    // Clear form
                    $('#form-item')[0].reset();
                    $('#id_item').val('').trigger('change');
                    $('#id_satuan').val('').trigger('change');
                    
                    // Refresh the page to show updated items
                    location.reload();
                } else {
                    // Show error message
                    toastr.error(response.message);
                    
                    // Show validation errors if any
                    if (response.errors) {
                        let errorMessage = '';
                        for (let field in response.errors) {
                            errorMessage += response.errors[field] + '<br>';
                        }
                        toastr.error(errorMessage);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax error:', error);
                console.error('XHR response:', xhr.responseText);
                toastr.error('Terjadi kesalahan saat menambahkan item');
            },
            complete: function() {
                // Re-enable submit button
                $('#form-item button[type="submit"]').prop('disabled', false)
                    .html('<i class="fas fa-plus mr-1"></i> Tambah');
            }
        });
    });

    // Load satuan options dynamically from JSON API
    loadSatuan();

    // Function to load satuan from /publik/satuan
    function loadSatuan() {
        $.ajax({
            url: '<?= base_url('publik/satuan') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // The API returns an array of satuan objects, not wrapped in {success:..., data:...}
                // So we expect response to be an array
                if (Array.isArray(response)) {
                    populateSatuanDropdown(response);
                } else if (response.data && Array.isArray(response.data)) {
                    // fallback if API ever wraps in {data: [...]}
                    populateSatuanDropdown(response.data);
                } else {
                    $('#id_satuan').empty().append('<option value="">Pilih Satuan</option>');
                }
            },
            error: function() {
                // Leave blank: do not populate satuan dropdown on error
                $('#id_satuan').empty().append('<option value="">Pilih Satuan</option>');
            }
        });
    }

    // Function to populate satuan dropdown
    function populateSatuanDropdown(satuans) {
        const satuanSelect = $('#id_satuan');
        satuanSelect.empty();
        satuanSelect.append('<option value="">Pilih Satuan</option>');
        
        satuans.forEach(function(satuan) {
            // satuan.id, satuan.satuanBesar, satuan.jml, satuan.kode may be present
            // Show satuanBesar, and optionally show jml if >1
            let label = satuan.satuanBesar;
            if (satuan.jml && satuan.jml > 1) {
                label += ` (${satuan.jml})`;
            }
            satuanSelect.append(`<option value="${satuan.id}">${label}</option>`);
        });
    }

    // Handle edit button click
    $(document).on('click', '.btn-edit', function() {
        const itemId = $(this).data('id');
        const row = $(this).closest('tr');
        
        // Get item data from the row
        const jmlText = row.find('td:eq(2)').text().trim();
        const jml = parseFloat(jmlText.split(' ')[0]) || 0;
        const satuan = jmlText.split(' ')[1] || 'PCS';
        
        const hargaText = row.find('td:eq(3)').text().trim();
        const harga = hargaText.replace(/[^\d]/g, '') || 0;
        
        const potonganText = row.find('td:eq(5)').text().trim();
        const potongan = potonganText.replace(/[^\d]/g, '') || 0;
        
        // Populate the form with item data
        $('#id_item').val(itemId).trigger('change');
        $('#jml').val(jml);
        $('#harga').val(harga);
        $('#potongan').val(potongan);
        
        // Set satuan after a short delay to ensure it's loaded
        setTimeout(function() {
            $('#id_satuan').val(satuan).trigger('change');
        }, 500);
        
        // Change form action to update instead of add
        $('#form-item').attr('action', '<?= base_url("transaksi/beli/cart_update/") ?>' + itemId);
        
        // Change button text
        $('#form-item button[type="submit"]').html('<i class="fas fa-edit mr-1"></i> Update');
        
        // Scroll to form
        $('html, body').animate({
            scrollTop: $('#form-item').offset().top - 100
        }, 500);
        
        // Show info message
        toastr.info('Item dipilih untuk diedit. Silakan ubah data dan klik Update.');
    });

    // Handle delete button click
    $(document).on('click', '.btn-delete', function() {
        const itemId = $(this).data('id');
        
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            $.ajax({
                url: '<?= base_url("transaksi/beli/cart_delete/") ?>' + itemId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Terjadi kesalahan saat menghapus item');
                }
            });
        }
    });

    // Reset form when adding new item (clear edit mode)
    $('#id_item').on('change', function() {
        if ($(this).val() === '') {
            // Reset form to add mode
            $('#form-item').attr('action', '<?= base_url("transaksi/beli/cart_add/{$transaksi->id}") ?>');
            $('#form-item button[type="submit"]').html('<i class="fas fa-plus mr-1"></i> Tambah');
            $('#form-item')[0].reset();
            $('#id_satuan').val('').trigger('change');
        }
    });
    
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>