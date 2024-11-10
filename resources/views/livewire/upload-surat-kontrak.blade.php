<div>
    <div class="font-medium mb-3">Anda bisa mengunggah dokumen, invoice, sertifikat, atau foto tambahan di sini.</div>
    <input type="file" wire:model="newAttachments" multiple class="hidden" id="fileUpload">
    <label for="fileUpload"
        class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
        + Unggah Lampiran
    </label>
    <div class="mt-3">
        @foreach ($attachments as $index => $attachment)
            <div class="flex  items-center justify-between border-b-4 p-2 rounded my-1">
                <span><span class="text-primary-600 me-3"> @php
                    $fileType = $attachment->getClientOriginalExtension();
                @endphp
                        @if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif']))
                            <i class="fa-solid fa-image text-green-500"></i>
                        @elseif($fileType == 'pdf')
                            <i class="fa-solid fa-file-pdf text-red-500"></i>
                        @elseif($fileType == 'doc' || $fileType == 'docx')
                            <i class="fa-solid fa-file-word text-blue-500"></i>
                        @else
                            <i class="fa-solid fa-file text-gray-500"></i>
                        @endif
                    </span><span>{{ $attachment->getClientOriginalName() }}</span></span>
                <button wire:click="removeAttachment({{ $index }})"
                    class="text-red-500 hover:text-red-700">&times;</button>
            </div>
        @endforeach
    </div>
</div>
