<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Certificate</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
    <h1>{{ $title }}</h1>

    <form id="certificate-form">
        @foreach ($certificateData as $pageIndex => $page)
            @foreach ($page['objects'] as $objectIndex => $object)
                <div>
                    <label for="input-{{ $pageIndex }}-{{ $objectIndex }}">Text Object {{ $pageIndex + 1 }}-{{ $objectIndex + 1 }}</label>
                    <input type="text" id="input-{{ $pageIndex }}-{{ $objectIndex }}" name="inputs[]" value="{{ $object['text'] }}">
                </div>
            @endforeach
        @endforeach
        <button type="button" id="download-btn">Download PDF</button>
    </form>

    <script>
        const { jsPDF } = window.jspdf;

        document.getElementById('download-btn').addEventListener('click', function() {
            const doc = new jsPDF({
                orientation: "landscape", // paisagem
                unit: "mm", // usando milímetros
                format: [303.02, 215.98] // largura x altura em milímetros
            });
            const certificateData = @json($certificateData);

            const loadImageAsBase64 = (url) => {
                return new Promise((resolve, reject) => {
                    const img = new Image();
                    img.setAttribute('crossOrigin', 'anonymous');
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        const dataURL = canvas.toDataURL('image/png');
                        resolve(dataURL);
                    };
                    img.onerror = reject;
                    img.src = url;
                });
            };

            const generatePDF = async () => {
                for (let pageIndex = 0; pageIndex < certificateData.length; pageIndex++) {
                    const page = certificateData[pageIndex];
                    
                    if (pageIndex > 0) {
                        doc.addPage();
                    }

                    const backgroundBase64 = await loadImageAsBase64(page.backgroundImage);
                    doc.addImage(backgroundBase64, 'PNG', 0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight());

                    page.objects.forEach((object, objectIndex) => {
                        const inputValue = document.getElementById(`input-${pageIndex}-${objectIndex}`).value;
                        const { xPos, yPos, fontSize, fontColor } = object;

                        doc.setFontSize(fontSize);
                        doc.setTextColor(fontColor);
                        doc.text(inputValue, xPos, yPos);
                    });
                }

                doc.save('{{ $title }}.pdf');
            };

            generatePDF();
        });
    </script>
</body>
</html>
