<div class="main-container">
	<!--form start-->
	<form name="bulk_upload_form" id="bulk_upload_form">
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Bulk Upload</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="upload_files">Uplaod Category</label><span class="text-danger">*</span>
											<select class="form-control" id="upload_files" name="upload_files" tabindex="1">
												<option value="">Select Uplaod Category</option>
												<option value="1">Group Creation</option>
												<option value="2">Customer Creation</option>
												<option value="3">Auction and Settlement</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="upload_btn">Upload Excel Here</label><span class="text-danger">*</span>
											<input type="file" class="form-control" id="upload_btn" name="upload_btn" accept=".csv,.xls,.xlsx,.xml" tabindex="2">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label style="visibility: hidden;" class="form-control"></label>
											<input type="button" class="btn btn-primary" value="Submit" name="bk_submit" id="bk_submit">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>