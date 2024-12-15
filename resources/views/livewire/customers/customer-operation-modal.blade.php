<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_customer_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Add Customer</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_customer_form" class="form" action="#" wire:submit="submit"
                    enctype="multipart/form-data">
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_customer_scroll"
                        data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                        data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="d-block fw-semibold fs-6 mb-5">Customer Photo</label>
                            <!--end::Label-->
                            <!--begin::Image placeholder-->
                            <style>
                                .image-input-placeholder {
                                    background-image: url('{{ image('svg/files/blank-image.svg') }}');
                                }

                                [data-bs-theme="dark"] .image-input-placeholder {
                                    background-image: url('{{ image('svg/files/blank-image-dark.svg') }}');
                                }
                            </style>
                            <!--end::Image placeholder-->
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline image-input-placeholder {{ $avatar || $saved_avatar ? '' : 'image-input-empty' }}" data-kt-image-input="true">
                                <!--begin::Preview existing avatar-->
                                @if($avatar)
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $avatar ? $avatar->temporaryUrl() : '' }});"></div>
                                @else
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $saved_avatar }});"></div>
                                @endif
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                    {!! getIcon('pencil','fs-7') !!}
                                    <!--begin::Inputs-->
                                    <input type="file" wire:model.defer="avatar" name="avatar" accept=".png, .jpg, .jpeg"/>
                                    <input type="hidden" name="avatar_remove"/>
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                    {!! getIcon('cross','fs-2') !!}
                                </span>
                                <!--end::Cancel-->
                                <!--begin::Remove-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                    {!! getIcon('cross','fs-2') !!}
                                </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                            @error('avatar')
                            <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">FULL NAME</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model="name" name="name"
                                class="form-control  border mb-3 mb-lg-0" placeholder="Full name" />
                            <!--end::Input-->
                            @error('name')
                            <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                     
                        <!--end::Input group-->
                        <div class="fv-row mb-2 d-flex flex-wrap">
                            <div class="col-12 col-lg-4 col-md-4 mb-7 mx-1">
                                <label for="id_type" class="required  fw-semibold fs-6 mb-2">ID TYPE</label>
                                <select name="id_type" id="id_type" wire:model="id_type"
                                    class="form-control  border mb-3 mb-lg-0">
                                    <option value="" selected>Select Id Type</option>
                                    <option value="Aadhaar">Aadhaar</option>
                                    <option value="Islander">Islander</option>
                                    <option value="PAN">PAN</option>
                                    <option value="License">License</option>
                                </select>
                                @error('id_type')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12 col-lg-6 col-md-6 mb-7">
                                <label for="" class="required fw-semibold fs-6 mb-2">ID NUMBER</label>
                                <input type="text" name="id_no" wire:model="id_no"
                                    class="form-control  border mb-3 mb-lg-0"
                                    placeholder="id proof nubmer">
                                @error('id_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fw-semibold fs-6 mb-2">PHOTO</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="file" wire:model="photo" name="photo"
                                class="form-control  border mb-3 mb-lg-0" wire:model="photo"
                               />
                            <!--end::Input-->
                            @error('photo')
                            <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    
                        <div class="fw-row mb-2">
                            <label for="" class="required fw-semibold fs-6 ">MOBILE NUMBER</label>
                            <div class="input-group mb-5">

                                <input type="text" class="form-control" placeholder="Customer's Mobile No."
                                    aria-describedby="basic-addon2"  wire:model="phone_no" />
                               
                                @error('phone_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>  
                        </div>
                        <div class="fv-row mb-7">
                        
                        <label class="fw-semibold fs-6 mb-2">ALT MOBILE</label>
                      
                        <input type="text" name="alt_mobile"
                            class="form-control  border mb-3 mb-lg-0" wire:model="alt_mobile"
                            placeholder="Alternate mobile" />
                  
                        @error('alt_mobile')
                        <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="fv-row mb-7">
                        
                        <label class="fw-semibold fs-6 mb-2">REMARKS</label>
                      
                        <input type="text" name="remarks"
                            class="form-control  border mb-3 mb-lg-0" wire:model="remarks"
                            placeholder="Remarks / Referrals" />
                  
                        @error('remarks')
                        <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                            wire:loading.attr="disabled">Discard</button>
                        <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label" wire:loading.remove>Submit</span>
                            <span class="indicator-progress" wire:loading wire:target="submit">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>