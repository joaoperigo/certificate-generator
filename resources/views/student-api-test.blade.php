<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste APIs - Certificate Students</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
                🧪 Teste APIs - Certificate Students
            </h1>
            
            <!-- Configuração -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">⚙️ Configuração:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Base URL:</label>
                        <input type="text" id="baseUrl" value="{{ url('') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Certificate ID:</label>
                        <input type="number" id="certificateId" value="1" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                </div>
            </div>

            <!-- Botões de teste -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <button onclick="testGenerateCode()" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    🎲 Gerar Código
                </button>
                <button onclick="testCreateStudent()" 
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    👤 Criar Estudante
                </button>
                <button onclick="testBatchStudents()" 
                        class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
                    👥 Criar Lote
                </button>
            </div>

            <!-- Formulário para estudante individual -->
            <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-3">📝 Dados do Estudante Individual:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" id="studentName" placeholder="Nome" value="João Silva"
                           class="px-3 py-2 border border-blue-300 rounded-md text-sm">
                    <input type="text" id="studentCpf" placeholder="CPF" value="123.456.789-00"
                           class="px-3 py-2 border border-blue-300 rounded-md text-sm">
                    <input type="text" id="studentUnit" placeholder="Unidade" value="Unidade Centro"
                           class="px-3 py-2 border border-blue-300 rounded-md text-sm">
                    <input type="text" id="studentCode" placeholder="Código (opcional)"
                           class="px-3 py-2 border border-blue-300 rounded-md text-sm">
                    <input type="date" id="startDate" value="2024-01-15"
                           class="px-3 py-2 border border-blue-300 rounded-md text-sm">
                    <input type="date" id="endDate" value="2024-02-15"
                           class="px-3 py-2 border border-blue-300 rounded-md text-sm">
                </div>
            </div>

            <!-- CSV para lote -->
            <div class="mb-6 bg-purple-50 p-4 rounded-lg">
                <h3 class="font-semibold text-purple-800 mb-3">📊 Dados CSV para Lote:</h3>
                <textarea id="csvData" rows="6" class="w-full px-3 py-2 border border-purple-300 rounded-md text-sm"
                          placeholder="name,cpf,unit,start_date,end_date">name,cpf,document,unit,start_date,end_date
João Silva,123.456.789-00,RG123456,Unidade Centro,2024-01-15,2024-02-15
Maria Santos,987.654.321-00,RG654321,Unidade Norte,2024-01-15,2024-02-15
Pedro Oliveira,111.222.333-44,RG111222,Unidade Sul,2024-01-15,2024-02-15</textarea>
            </div>

            <!-- Status da requisição -->
            <div id="status" class="mb-4 p-3 rounded-lg hidden">
                <span id="status-text"></span>
            </div>

            <!-- Resultados -->
            <div id="results-section" class="hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 Resultados:</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <pre id="results-json" class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto text-sm"></pre>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getBaseUrl() {
            return document.getElementById('baseUrl').value;
        }
        
        function getCertificateId() {
            return document.getElementById('certificateId').value;
        }
        
        async function testGenerateCode() {
            showStatus('loading', '🎲 Gerando código...');
            
            try {
                const response = await fetch(`${getBaseUrl()}/api/certificates/${getCertificateId()}/generate-code`);
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showStatus('success', `✅ Código gerado: ${data.code}`);
                    document.getElementById('studentCode').value = data.code;
                    displayResults(data);
                } else {
                    showStatus('error', `❌ Erro: ${data.message}`);
                    displayResults(data);
                }
            } catch (error) {
                showStatus('error', `❌ Erro de conexão: ${error.message}`);
            }
        }
        
        async function testCreateStudent() {
            showStatus('loading', '👤 Criando estudante...');
            
            const studentData = {
                name: document.getElementById('studentName').value,
                cpf: document.getElementById('studentCpf').value,
                unit: document.getElementById('studentUnit').value,
                code: document.getElementById('studentCode').value || undefined,
                start_date: document.getElementById('startDate').value,
                end_date: document.getElementById('endDate').value
            };
            
            // Gerar código se não fornecido
            if (!studentData.code) {
                try {
                    const codeResponse = await fetch(`${getBaseUrl()}/api/certificates/${getCertificateId()}/generate-code`);
                    const codeData = await codeResponse.json();
                    if (codeData.success) {
                        studentData.code = codeData.code;
                    }
                } catch (error) {
                    showStatus('error', '❌ Erro ao gerar código');
                    return;
                }
            }
            
            try {
                const response = await fetch(`${getBaseUrl()}/api/certificates/${getCertificateId()}/students`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(studentData)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showStatus('success', `✅ Estudante criado: ${data.data.name} (ID: ${data.data.id})`);
                } else {
                    showStatus('error', `❌ Erro: ${data.message}`);
                }
                
                displayResults(data);
                
            } catch (error) {
                showStatus('error', `❌ Erro de conexão: ${error.message}`);
            }
        }
        
        async function testBatchStudents() {
            showStatus('loading', '👥 Criando estudantes em lote...');
            
            const csvText = document.getElementById('csvData').value;
            const lines = csvText.trim().split('\n');
            const headers = lines[0].split(',');
            
            const students = lines.slice(1).map(line => {
                const values = line.split(',');
                const student = {};
                headers.forEach((header, index) => {
                    student[header.trim()] = values[index]?.trim();
                });
                return student;
            }).filter(student => student.name && student.start_date && student.end_date);
            
            try {
                const response = await fetch(`${getBaseUrl()}/api/certificates/${getCertificateId()}/students/batch`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ students })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showStatus('success', 
                        `✅ Lote processado: ${data.created_count} criados, ${data.error_count} erros`);
                } else {
                    showStatus('error', `❌ Erro: ${data.message}`);
                }
                
                displayResults(data);
                
            } catch (error) {
                showStatus('error', `❌ Erro de conexão: ${error.message}`);
            }
        }
        
        function showStatus(type, message) {
            const statusDiv = document.getElementById('status');
            const statusText = document.getElementById('status-text');
            
            statusDiv.className = 'mb-4 p-3 rounded-lg';
            
            switch(type) {
                case 'loading':
                    statusDiv.classList.add('bg-blue-50', 'border', 'border-blue-200', 'text-blue-700');
                    break;
                case 'success':
                    statusDiv.classList.add('bg-green-50', 'border', 'border-green-200', 'text-green-700');
                    break;
                case 'error':
                    statusDiv.classList.add('bg-red-50', 'border', 'border-red-200', 'text-red-700');
                    break;
            }
            
            statusText.textContent = message;
            statusDiv.classList.remove('hidden');
        }
        
        function displayResults(data) {
            document.getElementById('results-section').classList.remove('hidden');
            document.getElementById('results-json').textContent = JSON.stringify(data, null, 2);
        }
    </script>
</body>
</html>