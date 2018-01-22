function showPdf(that) {
    var id = $(that).parent("td").parent("tr").children("td:first-child").text();
    $.ajax({
        url: "./procedurePreview",
        type: "post",
        data: {id:id},
        success: function (res) {
            if(res.code === 1){
                var container = document.getElementById("pdf_container");
                container.style.display = "block";
                var url = res.path.substr(1);
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
            }else {
                layer.msg(res.msg);
            }
        }
    })

}