<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Certificate</title>
    <!-- Certifique-se de que o jsPDF seja carregado corretamente -->
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

    <script type="module">
        import {mangueiraRegular} from '../fonts/mangueiraRegular.js';
        import {mangueiraMedium} from '../fonts/mangueiraMedium.js';

        // Acesse o jsPDF usando o namespace correto
        document.getElementById('download-btn').addEventListener('click', async function() {
            const { jsPDF } = window.jspdf; // Corrige a definição de jsPDF

            // FONTS
            const callAddFontMangueiraRegular = function () {
            this.addFileToVFS('Fontspring-DEMO-mangueiraalt-semibold-normal.ttf', mangueiraRegular);
            this.addFont('Fontspring-DEMO-mangueiraalt-semibold-normal.ttf', 'Mangueira-Semibold', 'normal');
            };
            jsPDF.API.events.push(['addFonts', callAddFontMangueiraRegular])

            const callAddFontMangueiraMedium = function () {
            this.addFileToVFS('Mangueira-Medium-normal.ttf', mangueiraMedium);
            this.addFont('Mangueira-Medium-normal.ttf', 'Mangueira-Medium', 'normal');
            };
            jsPDF.API.events.push(['addFonts', callAddFontMangueiraMedium])

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

        // Verifique se a URL da imagem de fundo é válida
        if (page.backgroundImage) {
            try {
                const backgroundBase64 = await loadImageAsBase64(page.backgroundImage);
                doc.addImage(backgroundBase64, 'PNG', 0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight());
            } catch (error) {
                console.error(`Error loading background image for page ${pageIndex + 1}:`, error);
            }
        } else {
            console.warn(`No background image found for page ${pageIndex + 1}`);
        }

        page.objects.forEach((object, objectIndex) => {
            const inputValue = document.getElementById(`input-${pageIndex}-${objectIndex}`).value;
            const { xPos, yPos, fontSize, fontColor, boxWidth, letterSpacing, textAlign } = object;

            doc.setFontSize(fontSize);
            doc.setTextColor(fontColor);
            doc.setFont('Mangueira-Semibold', 'normal');

            let adjustedX = xPos / 3.77;
            if (textAlign === 'center') {
                adjustedX = xPos / 3.77 + (boxWidth / 3.77) / 2;
            } else if (textAlign === 'right') {
                adjustedX = xPos / 3.77 + boxWidth / 3.77;
            }

            if (boxWidth > 0) {
                doc.text(inputValue, adjustedX, yPos / 3.77, { maxWidth: boxWidth / 3.77, charSpace: letterSpacing, align: textAlign });
            } else {
                doc.text(inputValue, adjustedX, yPos / 3.77, { charSpace: letterSpacing, align: textAlign });
            }
        });
    }

    doc.save('{{ $title }}.pdf');
};


            await generatePDF();
        });
    </script>
</body>
</html>
