function showPdf() {
    var container = document.getElementById("container");
    container.style.display = "block";
    var url = '李晨.pdf';
    PDFJS.workerSrc = 'pdf.worker.js';
    PDFJS.getDocument(url).then(function getPdfHelloWorld(pdf) {
        pdf.getPage(1).then(function getPageHelloWorld(page) {
            var scale = 1;
            var viewport = page.getViewport(scale);
            var canvas = document.getElementById('the-canvas');
            var context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            var renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext);
        });
    });
}