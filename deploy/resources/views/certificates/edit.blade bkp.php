<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #editor-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin-top: 20px;
        }

        #controls {
            width: 300px;
            margin-right: 20px;
        }

        #controls h2 {
            margin-bottom: 10px;
        }

        #controls label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        #controls input, #controls select, #controls button {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        #canvas-container {
            flex-grow: 1;
            position: relative;
        }

        canvas {
            border: 1px solid #ccc;
            width: 100%;
            height: auto;
        }

        #object-list {
            margin-top: 20px;
        }

        #object-list h3 {
            margin-bottom: 10px;
        }

        #object-list ul {
            list-style-type: none;
            padding: 0;
        }

        #object-list li {
            padding: 5px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            cursor: pointer;
            border: 1px solid #ddd;
        }

        #object-list li.active {
            background-color: #e0e0e0;
            border-color: #bbb;
        }

        #object-properties {
            margin-top: 20px;
        }

        #object-properties h3 {
            margin-bottom: 10px;
        }

        #object-properties label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        #object-properties input, #object-properties select {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        #save-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        #save-buttons button {
            width: 48%;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }

    </style>
</head>
<body>

    <h1>Edit Certificate</h1>

    <form id="certificate-form" action="{{ route('certificates.update', $certificate->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div id="editor-container">

            <!-- Controls Section -->
            <div id="controls">
                <h2>Certificate Details</h2>

                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="{{ $certificate->title }}" required>

                <label for="page-select">Select Page:</label>
                <select id="page-select"></select>
                <button type="button" id="add-page-btn">Add New Page</button>
                <button type="button" id="delete-page-btn">Delete Current Page</button>

                <div id="object-list">
                    <h3>Objects on Page</h3>
                    <ul id="objects-ul"></ul>
                    <button type="button" id="add-text-btn">Add Text Object</button>
                </div>

                <div id="object-properties" style="display: none;">
                    <h3>Object Properties</h3>

                    <label for="obj-text">Text:</label>
                    <input type="text" id="obj-text">

                    <label for="obj-font-size">Font Size (px):</label>
                    <input type="number" id="obj-font-size" min="1">

                    <label for="obj-font-color">Font Color:</label>
                    <input type="color" id="obj-font-color">

                    <label for="obj-x">Position X:</label>
                    <input type="number" id="obj-x">

                    <label for="obj-y">Position Y:</label>
                    <input type="number" id="obj-y">

                    <label for="obj-width">Box Width (px):</label>
                    <input type="number" id="obj-width" min="1">

                    <button type="button" id="update-object-btn">Update Object</button>
                    <button type="button" id="delete-object-btn">Delete Object</button>
                </div>

            </div>

            <!-- Canvas Section -->
            <div id="canvas-container">
                <canvas id="certificate-canvas" width="1142" height="814"></canvas>
            </div>

        </div>

        <input type="hidden" id="data" name="data">

        <div id="save-buttons">
            <button type="button" id="generate-json-btn">Generate JSON</button>
            <button type="submit">Update Certificate</button>
        </div>

    </form>

    <script>
        // Initial Setup
        const canvas = document.getElementById('certificate-canvas');
        const ctx = canvas.getContext('2d');
        const form = document.getElementById('certificate-form');

        // Elements
        const pageSelect = document.getElementById('page-select');
        const addPageBtn = document.getElementById('add-page-btn');
        const deletePageBtn = document.getElementById('delete-page-btn');

        const objectsUl = document.getElementById('objects-ul');
        const addTextBtn = document.getElementById('add-text-btn');

        const objectProperties = document.getElementById('object-properties');
        const objTextInput = document.getElementById('obj-text');
        const objFontSizeInput = document.getElementById('obj-font-size');
        const objFontColorInput = document.getElementById('obj-font-color');
        const objXInput = document.getElementById('obj-x');
        const objYInput = document.getElementById('obj-y');
        const objWidthInput = document.getElementById('obj-width');
        const updateObjectBtn = document.getElementById('update-object-btn');
        const deleteObjectBtn = document.getElementById('delete-object-btn');

        const generateJsonBtn = document.getElementById('generate-json-btn');
        const dataInput = document.getElementById('data');

        // Data Structures
        let pages = [];
        let currentPageIndex = 0;
        let currentObjectIndex = null;

        // Initialize with existing data
        function initialize() {
            const existingData = {!! $certificate->data !!};

            if (existingData && existingData.length > 0) {
                pages = existingData;
            } else {
                // If no data, initialize with one empty page
                pages = [{ backgroundImage: null, objects: [] }];
            }

            populatePageSelect();
            renderCurrentPage();
        }

        // Populate Page Select Dropdown
        function populatePageSelect() {
            pageSelect.innerHTML = '';
            pages.forEach((page, index) => {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = `Page ${index + 1}`;
                pageSelect.appendChild(option);
            });
            pageSelect.selectedIndex = currentPageIndex;
        }

        // Render Current Page
        function renderCurrentPage() {
            const page = pages[currentPageIndex];
            const background = page.backgroundImage;

            // Clear Canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            if (background) {
                const bgImage = new Image();
                bgImage.src = background;
                bgImage.onload = () => {
                    ctx.drawImage(bgImage, 0, 0, canvas.width, canvas.height);
                    renderObjects();
                };
            } else {
                renderObjects();
            }

            populateObjectsList();
            clearObjectProperties();
        }

        // Render Objects on Canvas
        function renderObjects() {
            const objects = pages[currentPageIndex].objects;
            objects.forEach(obj => {
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
            });
        }

        // Populate Objects List
        function populateObjectsList() {
            objectsUl.innerHTML = '';
            const objects = pages[currentPageIndex].objects;

            objects.forEach((obj, index) => {
                const li = document.createElement('li');
                li.textContent = obj.text || `Object ${index + 1}`;
                li.dataset.index = index;
                li.classList.toggle('active', index === currentObjectIndex);
                li.addEventListener('click', () => selectObject(index));
                objectsUl.appendChild(li);
            });
        }

        // Select Object
        function selectObject(index) {
            currentObjectIndex = index;
            const obj = pages[currentPageIndex].objects[index];

            objTextInput.value = obj.text;
            objFontSizeInput.value = obj.fontSize;
            objFontColorInput.value = obj.fontColor;
            objXInput.value = obj.xPos;
            objYInput.value = obj.yPos;
            objWidthInput.value = obj.boxWidth;

            objectProperties.style.display = 'block';
            populateObjectsList();
        }

        // Clear Object Properties
        function clearObjectProperties() {
            currentObjectIndex = null;
            objTextInput.value = '';
            objFontSizeInput.value = '';
            objFontColorInput.value = '#000000';
            objXInput.value = '';
            objYInput.value = '';
            objWidthInput.value = '';
            objectProperties.style.display = 'none';
        }

        // Add New Page
        addPageBtn.addEventListener('click', () => {
            pages.push({ backgroundImage: null, objects: [] });
            currentPageIndex = pages.length - 1;
            populatePageSelect();
            renderCurrentPage();
        });

        // Delete Current Page
        deletePageBtn.addEventListener('click', () => {
            if (pages.length > 1) {
                pages.splice(currentPageIndex, 1);
                currentPageIndex = Math.max(currentPageIndex - 1, 0);
                populatePageSelect();
                renderCurrentPage();
            } else {
                alert('At least one page must exist.');
            }
        });

        // Change Page
        pageSelect.addEventListener('change', () => {
            currentPageIndex = parseInt(pageSelect.value);
            renderCurrentPage();
        });

        // Add Text Object
        addTextBtn.addEventListener('click', () => {
            const newObj = {
                text: 'New Text',
                fontSize: 20,
                fontColor: '#000000',
                xPos: 50,
                yPos: 50,
                boxWidth: 200
            };
            pages[currentPageIndex].objects.push(newObj);
            currentObjectIndex = pages[currentPageIndex].objects.length - 1;
            renderCurrentPage();
            selectObject(currentObjectIndex);
        });

        // Update Object
        updateObjectBtn.addEventListener('click', () => {
            if (currentObjectIndex !== null) {
                const obj = pages[currentPageIndex].objects[currentObjectIndex];
                obj.text = objTextInput.value;
                obj.fontSize = parseInt(objFontSizeInput.value);
                obj.fontColor = objFontColorInput.value;
                obj.xPos = parseInt(objXInput.value);
                obj.yPos = parseInt(objYInput.value);
                obj.boxWidth = parseInt(objWidthInput.value);
                renderCurrentPage();
            }
        });

        // Delete Object
        deleteObjectBtn.addEventListener('click', () => {
            if (currentObjectIndex !== null) {
                pages[currentPageIndex].objects.splice(currentObjectIndex, 1);
                clearObjectProperties();
                renderCurrentPage();
            }
        });

        // Generate JSON
        generateJsonBtn.addEventListener('click', () => {
            const jsonData = JSON.stringify(pages, null, 2);
            dataInput.value = jsonData;
            alert('JSON generated and ready for submission.');
        });

        // Initialize on page load
        window.onload = initialize;
    </script>

</body>
</html>
