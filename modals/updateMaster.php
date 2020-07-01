<div class="modal fade" id="updateMaster" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
 	  <button type="button" class="close" onclick="closeModal();" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    <div class="modal-content">
      <div class="modal-header text-center blue darken-1">
        <h4 class="modal-title w-100 font-weight-bold white-text "><span id="output"></span></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <!-- <form> -->
              <input type="hidden" id="id" name="id" class="form-control">
              <input type="hidden" id="master" name="master" class="form-control">
              <div class="col-lg-12">
                <label for="newName">Item Name</label>
                <input type="text" id="newName" name="newName" class="form-control">
              </div>
              <div class="form-row">
                <div class="col-lg-6">
                  <button class="btn btn-md btn-primary" id="btnUpdate" style="width: 100%"> <i class="fas fa-edit"></i> Update</button> 
                </div>
                <div class="col-lg-6">
                   <button class="btn btn-md btn-danger buttonref" style="width: 100%" id="btnDelete"><i class="fas fa-trash-alt"></i> Delete</button>
                </div>
              </div>
            <!-- </form> -->
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>