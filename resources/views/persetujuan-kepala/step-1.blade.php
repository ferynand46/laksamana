<div class="form-card text-start">
    <div class="row">
        <div class="col-7">
            <h3 class="mb-4">Account Information:</h3>
        </div>
        <div class="col-5">
            <h2 class="steps">Step 1 - 4</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="petugas_penerimaan">Petugas Penerimaan</label>
                <input type="text" class="form-control" name="residense_petugas" value="{{$residensial->nama_petugas}}" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Tanggal Masuk/Penerimaan: *</label>
                <input type="text" class="form-control" name="residense_tgl_penerimaan" value="{{date("d-m-Y",strtotime($residensial->tgl_penerimaan))}}" />
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label">Sumber Rujukan: *</label>
                <input type="text" class="form-control" name="residense_sumber" value="{{$residensial->sumber}}" />
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label">Gedung Asrama: *</label>
                <input type="text" class="form-control" name="residense_gedung" value="{{$residensial->nama_gedung}}" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Masa Layanan: *</label>
                <input type="text" class="form-control" name="residense_masa_layanan" placeholder="Masa Layanan" value="{{$residensial->masa_layanan}}"/>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Rencana Terminasi: *</label>
                <input type="text" class="form-control" name="residense_rencana_terminasi" placeholder="Rencana Terminasi" value="{{date("d-m-Y",strtotime($residensial->rencana_tgl_terminasi))}}"/>
            </div>
        </div>
        <div class="col-md-12 d-none">
            <div class="form-group">
                <label class="form-label">Nama Pengampu: *</label>
                <input type="text" class="form-control" name="residense_pengampu" placeholder="Nama Pengampu" value="{{$residensial->nama_pengampu}}"/>
            </div>
        </div>
    </div>
</div>