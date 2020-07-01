<div class="modal fade" id="updateMaster1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
 	  <button type="button" class="close" onclick="closeModal();" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    <div class="modal-content">
      <div class="modal-header text-center blue darken-1">
        <h4 class="modal-title w-100 font-weight-bold white-text "><span id="output1"></span></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <!-- <form> -->
              <input type="hidden" id="id1" name="id1" class="form-control">
              <input type="hidden" id="master1" name="master" class="form-control">
              <div class="form-row">
              <div class="col-lg-6">
                <label for="newName">Item Name 1</label>
                <input type="text" id="newItemName1" name="newItemName1"  class="form-control">
              </div>
              <div class="col-lg-6">
                <label for="newName">Item Name 2</label>
                <input type="text" id="newItemName2" name="newItemName2" class="form-control">
              </div>
            </div>
              <div class="form-row mt-1">
                <!-- <div class="col-lg-6"> -->
                  <button class="btn btn-md btn-primary" id="btnUpdate1" style="width: 100%"> <i class="fas fa-edit"></i>Update</button> 
               <!--  </div>
                <div class="col-lg-6"> -->
                   <button class="btn btn-md btn-danger" style="width: 100%" id="btnDelete1"><i class="fas fa-trash-alt"></i> Delete</button>
                </div>
              </div>
            <!-- </form> -->
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>