<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Certificate</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #controls {
            margin-bottom: 20px;
        }
        #img-upload-form {
            width: 10vw;
            right:0;
            top: 0;
            position: absolute;
        }
        #certificate-form {
            width: 33vw;
            left:0;
            top: 0;
            position: absolute;
        }
        canvas {
            width: 50vw;
            top: 50%;
            transform: translateY(-50%);
            left: 33vw;
            position: fixed;
            border: 1px solid black;
        }

        .object-controls {
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 300px;
        }

        .object-controls label {
            margin-right: 10px;
        }

        .object-controls button {
            margin-top: 5px;
        }
    </style>
</head>
<body>




<form id="img-upload-form" action="{{ route('images.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="image-upload">Upload Image:</label>
    <input type="file" name="image" id="image-upload">
    <button type="submit">Upload</button>
</form>


<form id="certificate-form" action="{{ route('certificates.store') }}" method="POST">
    @csrf
    <h1>Create Certificate</h1>
    <div id="controls">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <br><br>

        <label for="page-select">Page:</label>
        <select id="page-select" onchange="switchPage()">
            <option value="0">Page 1</option>
        </select>
        <button type="button" onclick="addPage()">Add Page</button>
        <br><br>

        <label for="bg-image">Background Image URL:</label>
        <!-- <input type="text" id="bg-image"> -->
        <label for="bg-image">Select Background Image:</label>
        <select  onchange="setImagePath(this)">
            @foreach($images as $image)
                <option value="{{ asset( $image->file_path) }}">{{ $image->name }}</option>
            @endforeach
        </select>
        <input type="text" id="bg-image">
        <img id="bg-image-src" width="100" height="80">

        <script>
            function setImagePath(select) {
                document.getElementById('bg-image').value = select.value;
                document.getElementById('bg-image-src').src = select.value;
            }
        </script>
        <button type="button" onclick="setBackground()">Load Image</button>
        <br><br>

        <label for="text">Text:</label>
        <input type="text" id="text">
        <!-- <textarea name="" id="" style="height: 20px"></textarea> -->
        <br>
        
        <label for="font-size">Font Size (px):</label>
        <input type="number" id="font-size">
        <br>
        
        <label for="font-color">Font Color:</label>
        <input type="color" id="font-color">
        <br>
        
        <label for="x-pos">Position X (mm):</label>
        <input type="number" id="x-pos">
        <br>
        
        <label for="y-pos">Position Y (mm):</label>
        <input type="number" id="y-pos">
        <br>
        
        <label for="letter-spacing">Letter Spacing</label>
        <input type="text" id="letter-spacing" value="0">
        <br>

        <label for="have-text-box">Have text box?</label>
        <input type="checkbox" id="have-text-box">
        <br>

        <!-- Campo "Box Width" que será exibido/ocultado -->
        <div id="box-width-container" style="display: none;">
            <label for="box-width">Box Width (mm):</label>
            <input type="number" id="box-width">
            <br>
            <label for="text-align">Font Family</label>
            <select id="text-align">
                <option value="left">left</option>
                <option value="center">center</option>
                <option value="right">right</option>
            </select>
            <br>
        </div>
        <br>

        <label for="font-family">Font Family</label>
        <select id="font-family">
            <option value="Mangueira-Semibold">Mangueira Regular</option>
            <option value="Mangueira-Medium">Mangueira Bold</option>
            <option value="Myriad-Medium">Myriad Medium</option>
        </select>
        <br>



        <!-- <label for="text-align">Text Align</label>
        <select id="text-align">
            <option value="left">left</option>
            <option value="center">center</option>
            <option value="right">right</option>
        </select> -->
        <br>
        <button type="button" onclick="addParagraph()">Add Paragraph</button>
    </div>

    <div id="object-list">
        <!-- The list of created objects will appear here -->
    </div>

    

    <input type="hidden" id="data" name="data">

    <br><br>
    <button type="button" onclick="generateJSON()">Generate JSON</button>
    <button type="submit">Save Certificate</button>
</form>

<canvas id="myCanvas" width="1142" height="814"></canvas>

<script>
    const canvas = document.getElementById('myCanvas');
    const ctx = canvas.getContext('2d');
    const mmToPx = 3.779528;
    const canvasWidth = 303.02 * mmToPx;
    const canvasHeight = 215.98 * mmToPx;
    canvas.width = canvasWidth;
    canvas.height = canvasHeight;

    let currentPage = 0;
    const pages = [
        {
            backgroundImage: null,
            objects: []
        }
    ];

    function setBackground() {
        const bgImage = document.getElementById('bg-image').value;
        const image = new Image();
        image.src = bgImage;
        image.onload = function() {
            pages[currentPage].backgroundImage = bgImage;
            redrawCanvas();
        }
    }

    function redrawCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const backgroundImage = pages[currentPage].backgroundImage;
        if (backgroundImage) {
            const image = new Image();
            image.src = backgroundImage;
            image.onload = function() {
                ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
                pages[currentPage].objects.forEach(drawText);
            }
        } else {
            pages[currentPage].objects.forEach(drawText);
        }
    }

    function addParagraph() {
        const text = document.getElementById('text').value;
        const fontSize = document.getElementById('font-size').value || 20;
        const fontColor = document.getElementById('font-color').value || '#2c2c2c';
        const xPos = parseInt(document.getElementById('x-pos').value) || 0;
        const yPos = parseInt(document.getElementById('y-pos').value) || 0;
        const boxWidth = parseInt(document.getElementById('box-width').value) || 0;
        const haveTextBox = parseInt(document.getElementById('have-text-box').value) || 0;
        const textAlign = document.getElementById('text-align').value || 'left';
        const letterSpacing = document.getElementById('letter-spacing').value || 0;
        const fontFamily = document.getElementById('font-family').value || 'Mangueira-Semibold';

        const obj = {
            text: text,
            fontSize: fontSize,
            fontColor: fontColor,
            xPos: xPos * mmToPx,  // Convert to pixels
            yPos: yPos * mmToPx,  // Convert to pixels
            boxWidth: boxWidth * mmToPx,  // Convert to pixels
            // haveTextBox: haveTextBox,
            letterSpacing: letterSpacing,
            fontFamily: fontFamily,
            textAlign: textAlign
        };

        pages[currentPage].objects.push(obj);
        drawText(obj);
        addObjectControls(obj);
    }

    function drawText(obj) {
        ctx.font = `${obj.fontSize}px Arial`;
        ctx.fillStyle = obj.fontColor;

        // Definir o alinhamento do texto conforme a opção escolhida
        ctx.textAlign = obj.textAlign || 'left'; // valor padrão é 'left' se não for especificado

        const words = obj.text.split(' ');
        let line = '';
        const lineHeight = obj.fontSize * 1.2;
        let y = obj.yPos;

        for (let i = 0; i < words.length; i++) {
            const testLine = line + words[i] + ' ';
            const metrics = ctx.measureText(testLine);
            const testWidth = metrics.width;

            if (testWidth > obj.boxWidth && i > 0 && obj.boxWidth > 0) {
                drawAlignedText(line, obj.xPos, y, obj);
                line = words[i] + ' ';
                y += lineHeight;
            } else {
                line = testLine;
            }
        }

        drawAlignedText(line, obj.xPos, y, obj);
    }

    // Função auxiliar para desenhar o texto com alinhamento
    function drawAlignedText(text, x, y, obj) {
        let adjustedX = x;

        if (obj.textAlign === 'center') {
            adjustedX = x + obj.boxWidth / 2; // centraliza o texto
        } else if (obj.textAlign === 'right') {
            adjustedX = x + obj.boxWidth; // alinha o texto à direita
        }

        ctx.fillText(text, adjustedX, y);
    }

    function addObjectControls(obj) {
        const objectList = document.getElementById('object-list');

        const objectDiv = document.createElement('div');
        objectDiv.className = 'object-controls';

        objectDiv.innerHTML = `
            <label>Text:</label>
            <input type="text" value="${obj.text}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'text')">
            <br>
            <label>Font Size (px):</label>
            <input type="number" value="${obj.fontSize}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'fontSize')">
            <br>
            <label>Font Color:</label>
            <input type="color" value="${obj.fontColor}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'fontColor')">
            <br>
            <label>Position X (mm):</label>
            <input type="number" value="${obj.xPos / mmToPx}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'xPos')">
            <br>
            <label>Position Y (mm):</label>
            <input type="number" value="${obj.yPos / mmToPx}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'yPos')">
            <br>
            <label>Box Width (mm):</label>
            <input type="number" value="${obj.boxWidth / mmToPx}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'boxWidth')">
            <br>
            <button type="button" onclick="deleteObject('${pages[currentPage].objects.indexOf(obj)}')">Delete</button>
        `;

        objectList.appendChild(objectDiv);
    }

    function updateObject(input, index, property) {
        pages[currentPage].objects[index][property] = property.includes('xPos') || property.includes('yPos') || property.includes('boxWidth') ? input.value * mmToPx : input.value;
        redrawCanvas();
    }

    function deleteObject(index) {
        pages[currentPage].objects.splice(index, 1);
        redrawCanvas();
        document.getElementById('object-list').innerHTML = ''; 
        pages[currentPage].objects.forEach(addObjectControls); 
    }

    function addPage() {
        pages.push({
            backgroundImage: null,
            objects: []
        });
        const pageSelect = document.getElementById('page-select');
        const newPageIndex = pages.length - 1;
        const newOption = document.createElement('option');
        newOption.value = newPageIndex;
        newOption.text = `Page ${newPageIndex + 1}`;
        pageSelect.add(newOption);
        pageSelect.value = newPageIndex;
        switchPage();
    }

    function switchPage() {
        currentPage = document.getElementById('page-select').value;
        redrawCanvas();
        document.getElementById('object-list').innerHTML = '';
        pages[currentPage].objects.forEach(addObjectControls);
    }

    document.getElementById('have-text-box').addEventListener('change', function() {
            // Obtém o estado atual do checkbox
            const isChecked = this.checked;
            // Obtém o container do campo Box Width
            const boxWidthContainer = document.getElementById('box-width-container');

            // Mostra ou oculta o campo "Box Width" baseado no estado do checkbox
            if (isChecked) {
                boxWidthContainer.style.display = 'block';
            } else {
                boxWidthContainer.style.display = 'none';
            }
    });

    function generateJSON() {
        const jsonOutput = JSON.stringify(pages, null, 2);
        document.getElementById('data').value = jsonOutput;
        // alert("JSON generated successfully! Ready to save.");
    }

    redrawCanvas(); // Redraw the initial page

    document.getElementById('certificate-form').addEventListener('keydown', function(event) {
        // Verifica se a tecla pressionada é Enter
        if (event.key === 'Enter') {
            event.preventDefault(); // Impede o comportamento padrão de submissão do formulário
        }
    });
</script>

</body>
</html>
