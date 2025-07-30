<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4" id="modalTitle">
                Kode Tiket Berhasil Dibuat!
            </h3>
            <div class="mt-4 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4" id="modalMessage">
                    Tiket berhasil dibuat dengan kode:
                </p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Kode Tiket</p>
                            <p class="text-xl font-bold text-gray-900" id="modalTicketCode">{{ $ticketCode ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Hadiah</p>
                            <p class="text-lg font-semibold text-blue-600" id="modalTicketPrize">{{ $ticketPrize ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button id="copyButton" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-copy mr-2"></i>
                        <span id="copyButtonText">Salin Kode</span>
                    </button>
                    <button id="closeModal" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('successModal');
    const copyButton = document.getElementById('copyButton');
    const copyButtonText = document.getElementById('copyButtonText');
    const closeButton = document.getElementById('closeModal');
    const ticketCodeElement = document.getElementById('modalTicketCode');
    
    // Copy to clipboard function
    copyButton.addEventListener('click', function() {
        const ticketCode = ticketCodeElement.textContent;
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(ticketCode).then(function() {
                copyButtonText.textContent = 'Tersalin!';
                copyButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                copyButton.classList.add('bg-green-600', 'hover:bg-green-700');
                
                setTimeout(function() {
                    copyButtonText.textContent = 'Salin Kode';
                    copyButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                    copyButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 2000);
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = ticketCode;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            copyButtonText.textContent = 'Tersalin!';
            copyButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            copyButton.classList.add('bg-green-600', 'hover:bg-green-700');
            
            setTimeout(function() {
                copyButtonText.textContent = 'Salin Kode';
                copyButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                copyButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 2000);
        }
    });
    
    // Close modal function
    closeButton.addEventListener('click', function() {
        modal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
    
    // Show modal function
    window.showSuccessModal = function(ticketCode, ticketPrize, isRandom = false) {
        document.getElementById('modalTicketCode').textContent = ticketCode;
        document.getElementById('modalTicketPrize').textContent = ticketPrize;
        
        if (isRandom) {
            document.getElementById('modalTitle').textContent = 'Tiket Random Berhasil Dibuat!';
            document.getElementById('modalMessage').textContent = 'Tiket random berhasil dibuat dengan kode:';
        } else {
            document.getElementById('modalTitle').textContent = 'Kode Tiket Berhasil Dibuat!';
            document.getElementById('modalMessage').textContent = 'Tiket berhasil dibuat dengan kode:';
        }
        
        modal.classList.remove('hidden');
    };
});
</script>