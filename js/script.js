/* drag and drop file upload */
const dropContainer = document.getElementById("dropcontainer");
const fileInput = document.getElementById("excel");

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
  dropContainer.addEventListener(eventName, preventDefaults, false)
});

function preventDefaults (e) {
  e.preventDefault()
  e.stopPropagation()
}

dropContainer.addEventListener("dragenter", () => {
  dropContainer.classList.add("drag-active");
});

dropContainer.addEventListener("dragleave", () => {
  dropContainer.classList.remove("drag-active");
});

dropContainer.addEventListener("drop", (e) => {
  dropContainer.classList.remove("drag-active");
  const files = e.dataTransfer.files;
  fileInput.files = files;
  // Jika Anda ingin memperbarui tampilan label "no file chosen"
  fileInput.dispatchEvent(new Event('change'));
});