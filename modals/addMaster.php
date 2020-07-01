<div class="modal fade" id="addMaster" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
 	  <button type="button" class="close" onclick="closeModal();" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    <div class="modal-content">
      <div class="modal-header text-center blue darken-1">
        <h4 class="modal-title w-100 font-weight-bold white-text "><span id="addOutput"></span></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
                <input type="hidden" id="addMasterList" name="masterList" class="form-control">
              <div class="md-form">
                <input type="text" id="addItem" name="addItem" class="form-control" required="">
                <label for="addItem">Item Name</label>
              </div>
              <div class="form-row mt-1">
                <div class="col-lg-12">
                  <button class="btn btn-md btn-primary" id="btnAddItem" style="width: 100%"><i class="fas fa-plus"></i> Add</button> 
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>