<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
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
        /* #img-upload-form {
            width: 10vw;
            right:0;
            top: 0;
            position: absolute;
        } */
        #certificate-form {
            width: 25vw;
            left:0;
            top: 0;
            position: fixed;
            height: 100vh; /* viewport height */
            overflow-y: scroll;
            overflow-x: hidden;
        }
        canvas {
            width: 50vw;
            height: auto;
            top: 50%;
            transform: translate(-50%, -50%);
            left: 50%;
            position: fixed;
            border: 1px solid black;
        }
        .sidebar-2 {
            width: 25vw;
            position: fixed;
            right: 0;
            top:0;
            height: 100vh; /* viewport height */
            overflow-y: scroll;
            overflow-x: hidden;
        }
        .object-controls {
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            /* width: 300px; */
        }

        .object-controls label {
            margin-right: 10px;
        }

        .object-controls button {
            margin-top: 5px;
        }
    </style>





<form class="px-6" id="certificate-form" action="{{ route('certificates.store') }}" method="POST">
    @csrf
    <h1 class="text-3xl font-bold pt-8 mb-8">Create Certificate</h1>
    <div id="controls">

        <div class="mb-4">
            <label class="block text-gray-700 text-base font-bold mb-2" for="title">Certificate title:</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="title" name="title" placeholder="Certificate title here" required>
        </div>
        <hr>
        <div class="pt-8 pb-4">
            <label class="block text-gray-700 text-base font-bold mb-2" for="page-select">Certificate Page(s):</label>
            <select id="page-select" onchange="switchPage()">
                <option value="0">Page 1</option>
            </select>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="button" onclick="addPage()">Add Page</button>
        </div>
        <hr>
        
        <div class="mt-8 mb-4" id="app">
            <image-uploader></image-uploader>
            <div class="text-end">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="button" onclick="setBackground()">Insert image >></button>
                <p class="text-small">Choose the background image of the certificate page</p>
            </div>
        </div>
        
 

    </div>

    <input type="hidden" id="data" name="data">

    <br><br>
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mb-4" type="button" onclick="generateJSON()">Generate JSON</button>
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mb-16" type="submit">Save Certificate</button>
</form>

<canvas id="myCanvas"></canvas>

<div class="sidebar-2 px-5">
       <!-- // begin create object -->
       <hr>
        <h2 class="text-xl mt-8 font-bold mb-2" >Create object</h2>
        <div class="">
            <label class="block text-gray-700 text-base font-bold mb-2" for="object-name">Name of the object:</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="object-name">
        </div>
        <div class="">
            <label class="block text-gray-700 text-base font-bold mb-2" for="text">Default text:</label>
            <!-- <input type="text" id="text"> -->
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="text"></textarea>
        </div>
        
        <div class="columns-2">
            <div>
                <label class="w-full" for="font-size">Font Size (px):</label>
                <input class="w-full" type="number" id="font-size">
            </div>

            
            <div>
                <label class="w-full" for="font-color">Font Color:</label>
                <input class="w-full" type="color" id="font-color">
            </div>
        </div>

        <div class="columns-2">
            <div>
                <label class="w-full" for="x-pos">Position X (mm):</label>
                <input class="w-full" type="number" id="x-pos">
            </div>
    
            <div>
                <label class="w-full" for="y-pos">Position Y (mm):</label>
                <input class="w-full" type="number" id="y-pos">
            </div>        
  
        </div>

        <div class="columns-2">
            <div>
                <label class="w-full" for="letter-spacing">Letter Spacing</label>
                <input class="w-full" type="text" id="letter-spacing" value="0">
            </div>
            <div>
                <label class="w-full" for="text-align">Text Align</label>
                <select class="w-full" id="text-align">
                    <option value="left">left</option>
                    <option value="center">center</option>
                    <option value="right">right</option>
                </select>
            </div>
        </div> 

        <div class="columns-2">
            <div>
                <label class="w-full" for="have-text-box">Have text box?</label>
                <div class="text-start w-full">
                    <input type="checkbox" id="have-text-box">
                </div>
            </div>

            <!-- Campo "Box Width" que será exibido/ocultado -->
            <div id="box-width-container" style="display: none;">
                <label class="w-full" for="box-width">Box Width (mm):</label>
                <input class="w-full" type="number" id="box-width">
            </div>
        </div>

        <div>
            <label class="w-full" for="font-family">Font Family</label>
            <select class="w-full" id="font-family">
                <option value="Mangueira-Semibold">Mangueira Regular</option>
                <option value="Mangueira-Medium">Mangueira Bold</option>
                <option value="Myriad-Medium">Myriad Medium</option>
            </select>
        </div>


        <!-- <label for="text-align">Text Align</label>
        <select id="text-align">
            <option value="left">left</option>
            <option value="center">center</option>
            <option value="right">right</option>
        </select> -->
        <br>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6" type="button" onclick="addParagraph()">+ Add Paragraph</button>
        <!-- // end create object -->
         <div class="w-full bg-blue-900 h-10 sticky top-0"></div>
    <div class="w-full py-10" id="object-list">
            <!-- The list of created objects will appear here -->
    </div>
</div>

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
        const objectName = document.getElementById('object-name').value;
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
            objectName: objectName,
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
            <label>Object name:</label>
                <input class="w-full" type="text" value="${obj.objectName}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'text')">
            </label>
            <label>Text:
                <textarea class="w-full" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'text')">${obj.text}</textarea>
            </label>
            <div class="columns-2">
                <div>
                    <label class="w-full">Font Size (px):</label>
                    <input class="w-full" type="number" value="${obj.fontSize}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'fontSize')">
                </div>
                <div>
                    <label>Font Color:</label>
                    <input type="color" value="${obj.fontColor}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'fontColor')">
                </div>
            </div>
            <label>Position X (mm):</label>
            <input type="number" value="${obj.xPos / mmToPx}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'xPos')">
            <br>
            <label>Position Y (mm):</label>
            <input type="number" value="${obj.yPos / mmToPx}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'yPos')">
            <br>
            <label>Box Width (mm):</label>
            <input type="number" value="${obj.boxWidth / mmToPx}" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'boxWidth')">
            <label class="w-full" for="font-family">Font Family
                <select class="w-full" onchange="updateObject(this, '${pages[currentPage].objects.indexOf(obj)}', 'fontFamily')">
                    <option value="Mangueira-Semibold">Mangueira Regular</option>
                    <option value="Mangueira-Medium">Mangueira Bold</option>
                    <option value="Myriad-Medium">Myriad Medium</option>
                </select>
            </label>
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

    // Evitar submissão do form por clicar em qualquer botão
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('certificate-form');
        form.addEventListener('submit', function(event) {
            // Verifica se o botão clicado é o botão de submit do formulário
            if (event.submitter && event.submitter.getAttribute('type') !== 'submit') {
                event.preventDefault();
            } else {
                generateJSON(); // salva o json antes de submeter
            }
        });
    });
</script>
@vite('resources/js/app.js')
</x-app-layout>