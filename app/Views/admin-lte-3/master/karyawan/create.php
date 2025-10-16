<?php
/**
 * Created by:
 * Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * 2025-01-17
 * 
 * Karyawan Create View
 */
?>
<?= $this->extend(theme_path('main')) ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <?= form_open('master/karyawan/store') ?>
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Form Data Karyawan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Kode -->
                        <div class="form-group">
                            <label>Kode</label>
                            <?= form_input([
                                'name' => 'kode',
                                'type' => 'text',
                                'class' => 'form-control rounded-0',
                                'value' => $kode,
                                'readonly' => true
                            ]) ?>
                        </div>
                        <!-- NIK -->
                        <div class="form-group">
                            <label>NIK <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <?= form_input([
                                    'name' => 'nik',
                                    'id' => 'nik',
                                    'type' => 'text',
                                    'class' => 'form-control rounded-0 ' . ($validation->hasError('nik') ? 'is-invalid' : ''),
                                    'placeholder' => 'Nomor Identitas...',
                                    'value' => old('nik')
                                ]) ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('nik') ?>
                                </div>
                            </div>
                        </div>
                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'nama',
                                'type' => 'text',
                                'class' => 'form-control rounded-0 ' . ($validation->hasError('nama') ? 'is-invalid' : ''),
                                'placeholder' => 'Nama lengkap karyawan...',
                                'value' => old('nama')
                            ]) ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('nama') ?>
                            </div>
                        </div>
                        <!-- Nama Panggilan -->
                        <div class="form-group">
                            <label>Nama Panggilan</label>
                            <?= form_input([
                                'name' => 'nama_pgl',
                                'type' => 'text',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Nama panggilan...',
                                'value' => old('nama_pgl')
                            ]) ?>
                        </div>
                        <!-- Tempat & Tanggal Lahir -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tmp Lahir <span class="text-danger">*</span></label>
                                    <?= form_input([
                                        'name' => 'tmp_lahir',
                                        'type' => 'text',
                                        'class' => 'form-control rounded-0 ' . ($validation->hasError('tmp_lahir') ? 'is-invalid' : ''),
                                        'placeholder' => 'Semarang...',
                                        'value' => old('tmp_lahir')
                                    ]) ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('tmp_lahir') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Tgl Lahir <span class="text-danger">*</span></label>
                                    <?= form_input([
                                        'name' => 'tgl_lahir',
                                        'type' => 'date',
                                        'class' => 'form-control rounded-0 ' . ($validation->hasError('tgl_lahir') ? 'is-invalid' : ''),
                                        'value' => old('tgl_lahir')
                                    ]) ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('tgl_lahir') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <!-- Jenis Kelamin -->
                                <div class="form-group">
                                    <label>L/P <span class="text-danger">*</span></label>
                                    <?= form_dropdown(
                                        'jns_klm',
                                        [
                                            '' => '- Pilih -',
                                            'L' => 'Laki-laki',
                                            'P' => 'Perempuan'
                                        ],
                                        old('jns_klm'),
                                        'class="form-control rounded-0 ' . ($validation->hasError('jns_klm') ? 'is-invalid' : '') . '"'
                                    ) ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('jns_klm') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Alamat KTP -->
                        <div class="form-group">
                            <label>Alamat KTP</label>
                            <?= form_textarea([
                                'name' => 'alamat',
                                'class' => 'form-control rounded-0',
                                'rows' => 5,
                                'placeholder' => 'Mohon diisi alamat lengkap sesuai ktp...',
                                'value' => old('alamat')
                            ]) ?>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?= form_label('RT', 'rt') ?>
                                    <?= form_input([
                                        'type' => 'text',
                                        'class' => 'form-control rounded-0',
                                        'id' => 'rt',
                                        'name' => 'rt',
                                        'maxlength' => 3,
                                        'placeholder' => 'RT',
                                        'value' => old('rt')
                                    ]) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?= form_label('RW', 'rw') ?>
                                    <?= form_input([
                                        'type' => 'text',
                                        'class' => 'form-control rounded-0',
                                        'id' => 'rw',
                                        'name' => 'rw',
                                        'maxlength' => 3,
                                        'placeholder' => 'RW',
                                        'value' => old('rw')
                                    ]) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= form_label('Kelurahan', 'kelurahan') ?>
                                    <?= form_input([
                                        'type' => 'text',
                                        'class' => 'form-control rounded-0',
                                        'id' => 'kelurahan',
                                        'name' => 'kelurahan',
                                        'placeholder' => 'Masukkan kelurahan',
                                        'value' => old('kelurahan')
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= form_label('Kecamatan', 'kecamatan') ?>
                                    <?= form_input([
                                        'type' => 'text',
                                        'class' => 'form-control rounded-0',
                                        'id' => 'kecamatan',
                                        'name' => 'kecamatan',
                                        'placeholder' => 'Masukkan kecamatan',
                                        'value' => old('kecamatan')
                                    ]) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= form_label('Kota', 'kota') ?>
                                    <?= form_input([
                                        'type' => 'text',
                                        'class' => 'form-control rounded-0',
                                        'id' => 'kota',
                                        'name' => 'kota',
                                        'placeholder' => 'Masukkan kota',
                                        'value' => old('kota')
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Jabatan -->
                        <div class="form-group">
                            <label>Jabatan <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'jabatan',
                                'type' => 'text',
                                'class' => 'form-control rounded-0 ' . ($validation->hasError('jabatan') ? 'is-invalid' : ''),
                                'placeholder' => 'Jabatan karyawan...',
                                'value' => old('jabatan')
                            ]) ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('jabatan') ?>
                            </div>
                        </div>
                        <!-- User Group -->
                        <div class="form-group">
                            <label>User Group</label>
                            <select name="id_user_group" class="form-control rounded-0">
                                <option value="">- Pilih -</option>
                                <?php foreach ($jabatans as $jabatan): ?>
                                    <option value="<?= $jabatan->id ?>" <?= old('id_user_group') == $jabatan->id ? 'selected' : '' ?>>
                                        <?= $jabatan->description ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- No HP -->
                        <div class="form-group">
                            <label>No. HP <span class="text-danger">*</span></label>
                            <?= form_input([
                                'name' => 'no_hp',
                                'type' => 'text',
                                'class' => 'form-control rounded-0 ' . ($validation->hasError('no_hp') ? 'is-invalid' : ''),
                                'placeholder' => 'Nomor kontak karyawan...',
                                'value' => old('no_hp')
                            ]) ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('no_hp') ?>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="form-group">
                            <label>Email</label>
                            <?= form_input([
                                'name' => 'email',
                                'type' => 'email',
                                'class' => 'form-control rounded-0',
                                'placeholder' => 'Alamat email karyawan...',
                                'value' => old('email')
                            ]) ?>
                        </div>
                        <!-- Alamat Domisili -->
                        <div class="form-group">
                            <label>Alamat Domisili</label>
                            <?= form_textarea([
                                'name' => 'alamat_domisili',
                                'class' => 'form-control rounded-0',
                                'rows' => 5,
                                'placeholder' => 'Alamat tempat tinggal saat ini...',
                                'value' => old('alamat_domisili')
                            ]) ?>
                        </div>
                        <!-- Upload Foto -->
                        <div class="form-group">
                            <label>Foto Karyawan</label>
                            <input type="file" name="file_foto" class="form-control-file">
                            <?php if ($validation->hasError('file_foto')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('file_foto') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Status -->
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control rounded-0">
                                <option value="">- Pilih -</option>
                                <option value="1" <?= old('status') == '1' ? 'selected' : '' ?>>Kasir</option>
                                <option value="2" <?= old('status') == '2' ? 'selected' : '' ?>>Supervisor / Kepala Toko</option>
                                <option value="3" <?= old('status') == '3' ? 'selected' : '' ?>>Gudang / Stocker</option>
                                <option value="4" <?= old('status') == '4' ? 'selected' : '' ?>>Admin Penjualan</option>
                                <option value="5" <?= old('status') == '5' ? 'selected' : '' ?>>Purchasing</option>
                                <option value="6" <?= old('status') == '6' ? 'selected' : '' ?>>Owner / Manajer</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-left">
                <a href="<?= base_url('master/karyawan') ?>" class="btn btn-default rounded-0">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary rounded-0 float-right">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>