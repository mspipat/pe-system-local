<div class="modal fade" id="uploadModal" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-md">
 	<button type="button" class="close" onclick="closeModal();" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
  <div class="modal-content">
    <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold white-text"><span id="output"> &nbsp;</span> (Upload File) </h4>
    </div>
    <div class="modal-body">
      <form method="post" enctype="multipart/form-data" id="fileUploadForm">
      		<div class="form-row">
      				<div class="input-group text">
								  	<div class="input-group-prepend">
								    <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
								  	</div>
									  <div class="custom-file">
									    <input type="file" class="custom-file-input" id="dataExcel"
									      aria-describedby="inputGroupFileAddon01" accept="application/vnd.ms-excel,.xlsx" name="file">
									    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
									  </div>
										</div>
								</div>
      		<div class="form-row">
      			<div class="col-lg-12">
											<button class="btn btn-md mt-2" id="btnSubmit">Submit</button>
      			</div>
							</div>
      		<input type="hidden" name="outputData" id="outputData">
      </form>
    </div>
  </div>
	</div>
</div>