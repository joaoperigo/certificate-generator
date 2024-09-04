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

        canvas {
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

<h1>Create Certificate</h1>

<form id="certificate-form" action="{{ route('certificates.store') }}" method="POST">
    @csrf
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
        <input type="text" id="bg-image">
        <button type="button" onclick="setBackground()">Load Image</button>
        <br><br>

        <label for="text">Text:</label>
        <input type="text" id="text">
        
        <label for="font-size">Font Size (px):</label>
        <input type="number" id="font-size">
        
        <label for="font-color">Font Color:</label>
        <input type="color" id="font-color">
        
        <label for="x-pos">Position X (mm):</label>
        <input type="number" id="x-pos">
        
        <label for="y-pos">Position Y (mm):</label>
        <input type="number" id="y-pos">
        
        <label for="have-text-box">Have text box?</label>
        <input type="checkbox" id="have-text-box">

        <!-- Campo "Box Width" que será exibido/ocultado -->
        <div id="box-width-container" style="display: none;">
            <label for="box-width">Box Width (mm):</label>
            <input type="number" id="box-width">
        </div>

        <label for="font-famil">Font Family</label>
        <select id="font-family">
            <option value="0">Mangueira Regular</option>
            <option value="1">Mangueira Bold</option>
        </select>

        <label for="letter-spacing">Letter Spacing</label>
        <input type="text" id="letter-spacing">

        <label for="text-align">Text Align</label>
        <select id="text-align">
            <option value="0">left</option>
            <option value="1">center</option>
            <option value="2">right</option>
        </select>

        <button type="button" onclick="addParagraph()">Add Paragraph</button>
        <button type="button" onclick="generateJSON()">Generate JSON</button>
    </div>

    <div id="object-list">
        <!-- The list of created objects will appear here -->
    </div>

    <canvas id="myCanvas" width="1142" height="814"></canvas>

    <input type="hidden" id="data" name="data">

    <br><br>
    <button type="submit">Save Certificate</button>
</form>

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

        const obj = {
            text: text,
            fontSize: fontSize,
            fontColor: fontColor,
            xPos: xPos * mmToPx,  // Convert to pixels
            yPos: yPos * mmToPx,  // Convert to pixels
            boxWidth: boxWidth * mmToPx  // Convert to pixels
        };

        pages[currentPage].objects.push(obj);
        drawText(obj);
        addObjectControls(obj);
    }

    function drawText(obj) {
        ctx.font = `${obj.fontSize}px Arial`;
        ctx.fillStyle = obj.fontColor;

        const words = obj.text.split(' ');
        let line = '';
        const lineHeight = obj.fontSize * 1.2;
        let y = obj.yPos;

        for (let i = 0; i < words.length; i++) {
            const testLine = line + words[i] + ' ';
            const metrics = ctx.measureText(testLine);
            const testWidth = metrics.width;

            if (testWidth > obj.boxWidth && i > 0) {
                ctx.fillText(line, obj.xPos, y);
                line = words[i] + ' ';
                y += lineHeight;
            } else {
                line = testLine;
            }
        }

        ctx.fillText(line, obj.xPos, y);
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
        alert("JSON generated successfully! Ready to save.");
    }

    redrawCanvas(); // Redraw the initial page
</script>

</body>
</html>
