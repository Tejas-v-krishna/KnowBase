<x-app-layout>
    <x-slot name="title">Account Settings</x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold font-outfit text-slate-900">Account Settings</h1>
            <p class="text-sm text-slate-500">Update your public profile details</p>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <!-- Include Cropper.js CDN -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" id="profile-form" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Hidden field to hold cropped base64 data -->
                <input type="hidden" name="cropped_avatar" id="cropped_avatar" />

                <!-- Avatar Field -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 font-outfit mb-3">Profile Avatar</label>
                    <div class="flex items-center space-x-6">
                        <div class="shrink-0 relative group">
                            <div id="avatar-preview-container">
                                <x-user-avatar :user="$user" class="h-20 w-20 rounded-2xl object-cover border border-slate-200" id="avatar-preview" />
                            </div>
                        </div>
                        <div class="space-y-1.5 flex-1">
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-xs text-slate-600
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-xl file:border-0
                                file:text-xs file:font-semibold
                                file:bg-slate-100 file:text-slate-700
                                hover:file:bg-slate-200
                                cursor-pointer" />
                            <p class="text-[11px] text-slate-400">PNG, JPG, or GIF. Max 2MB.</p>
                            @error('avatar')
                                <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror

                            @if($user->avatar)
                                <div class="flex items-center space-x-2 mt-2 bg-rose-50 border border-rose-100/60 rounded-xl px-3 py-1.5 w-fit">
                                    <input type="checkbox" name="remove_avatar" id="remove_avatar" value="1" class="rounded border-rose-300 text-rose-600 focus:ring-rose-500 bg-white cursor-pointer h-4 w-4" />
                                    <label for="remove_avatar" class="text-xs font-bold text-rose-600 cursor-pointer select-none hover:text-rose-700">Delete Profile Photo</label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Name Field -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-sm font-bold text-slate-900 font-outfit">Display Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white text-sm text-slate-900 focus:ring-2 focus:ring-slate-100 focus:border-slate-300 outline-none transition-all" required />
                    @error('name')
                        <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username Field (Readonly/Disabled) -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-900 font-outfit">Username</label>
                    <input type="text" value="{{ $user->username }}" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-100 text-sm text-slate-500 cursor-not-allowed outline-none" disabled />
                    <p class="text-[10px] text-slate-400">Username cannot be changed once registered.</p>
                </div>

                <!-- Bio Field -->
                <div class="space-y-1.5">
                    <label for="bio" class="block text-sm font-bold text-slate-900 font-outfit">Short Bio</label>
                    <textarea name="bio" id="bio" rows="4" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white text-sm text-slate-900 focus:ring-2 focus:ring-slate-100 focus:border-slate-300 outline-none transition-all" placeholder="Tell the community about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('users.show', $user->username) }}" class="inline-flex justify-center items-center px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 text-sm font-semibold rounded-xl transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Modern Premium Cropping Modal (Modal Backing) -->
        <div id="cropper-modal" class="fixed inset-0 z-50 overflow-y-auto hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold font-outfit text-slate-900">Crop Profile Photo</h3>
                    <button type="button" id="close-modal-btn" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="overflow-hidden bg-slate-50 border border-slate-100 rounded-2xl max-h-[350px] flex items-center justify-center">
                    <img id="cropper-image" class="max-w-full block" style="max-height: 300px;" />
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div class="flex items-center space-x-2">
                        <button type="button" id="zoom-in-btn" class="p-2 border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors" title="Zoom In">
                            <svg class="h-4.5 w-4.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                        <button type="button" id="zoom-out-btn" class="p-2 border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors" title="Zoom Out">
                            <svg class="h-4.5 w-4.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <button type="button" id="rotate-btn" class="p-2 border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors" title="Rotate 90°">
                            <svg class="h-4.5 w-4.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3 3 3m-3-3v12"/></svg>
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" id="cancel-crop-btn" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="apply-crop-btn" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-colors shadow-sm">
                            Apply & Crop
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cropper JS Execution -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const avatarInput = document.getElementById('avatar');
                const croppedAvatarInput = document.getElementById('cropped_avatar');
                const avatarPreview = document.getElementById('avatar-preview');
                const removeAvatarCheckbox = document.getElementById('remove_avatar');

                const modal = document.getElementById('cropper-modal');
                const cropperImage = document.getElementById('cropper-image');
                const closeBtn = document.getElementById('close-modal-btn');
                const cancelBtn = document.getElementById('cancel-crop-btn');
                const applyBtn = document.getElementById('apply-crop-btn');
                
                const zoomInBtn = document.getElementById('zoom-in-btn');
                const zoomOutBtn = document.getElementById('zoom-out-btn');
                const rotateBtn = document.getElementById('rotate-btn');

                let cropper = null;

                // Handle Delete Profile Photo checkbox click
                if (removeAvatarCheckbox) {
                    removeAvatarCheckbox.addEventListener('change', function () {
                        const currentPreview = document.getElementById('avatar-preview');
                        if (currentPreview) {
                            currentPreview.style.opacity = this.checked ? '0.3' : '1';
                        }
                    });
                }

                avatarInput.addEventListener('change', function (e) {
                    const files = e.target.files;
                    if (files && files.length > 0) {
                        const file = files[0];
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const currentPreview = document.getElementById('avatar-preview');
                            // Reset remove checkbox if checked
                            if (removeAvatarCheckbox && removeAvatarCheckbox.checked) {
                                removeAvatarCheckbox.checked = false;
                                if (currentPreview) {
                                    currentPreview.style.opacity = '1';
                                }
                            }

                            // Load image in Cropper Image element
                            cropperImage.src = e.target.result;
                            
                            // Show modal
                            modal.classList.remove('hidden');
                            
                            // Initialize Cropper.js
                            if (cropper) {
                                cropper.destroy();
                            }
                            
                            cropper = new Cropper(cropperImage, {
                                aspectRatio: 1, // Locked 1:1 square ratio
                                viewMode: 1, // Restrict crop box within canvas
                                background: true,
                                autoCropArea: 0.9,
                                responsive: true,
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Zoom & Rotate Controls
                zoomInBtn.addEventListener('click', () => cropper && cropper.zoom(0.1));
                zoomOutBtn.addEventListener('click', () => cropper && cropper.zoom(-0.1));
                rotateBtn.addEventListener('click', () => cropper && cropper.rotate(90));

                function closeModal() {
                    modal.classList.add('hidden');
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    avatarInput.value = ''; // Reset file input so same file can trigger change
                }

                closeBtn.addEventListener('click', closeModal);
                cancelBtn.addEventListener('click', closeModal);

                applyBtn.addEventListener('click', function () {
                    if (!cropper) return;

                    // Get Cropped Canvas (locked to beautiful 300x300 for crisp load size)
                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high',
                    });

                    if (canvas) {
                        const dataUrl = canvas.toDataURL('image/jpeg');
                        
                        // Set the hidden input field value
                        croppedAvatarInput.value = dataUrl;
                        
                        // Update preview element to show cropped image immediately
                        const previewContainer = document.getElementById('avatar-preview-container');
                        if (previewContainer) {
                            previewContainer.innerHTML = `<img src="${dataUrl}" class="h-20 w-20 rounded-2xl object-cover border border-slate-200" id="avatar-preview" />`;
                        } else {
                            const currentPreview = document.getElementById('avatar-preview');
                            if (currentPreview) {
                                currentPreview.src = dataUrl;
                                currentPreview.style.opacity = '1';
                            }
                        }
                        
                        // Close modal
                        modal.classList.add('hidden');
                        cropper.destroy();
                        cropper = null;
                    }
                });
            });
        </script>
    </div>
</x-app-layout>
