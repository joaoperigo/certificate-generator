<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste API - Certificados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
                🧪 Teste da API - Certificados
            </h1>
            
            <!-- Botões de teste -->
            <div class="mb-6 flex gap-4 justify-center">
                <button 
                    onclick="loadCertificates()" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors"
                >
                    📋 Carregar Certificados
                </button>
                <button 
                    onclick="clearResults()" 
                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors"
                >
                    🗑️ Limpar
                </button>
            </div>

            <!-- Status da requisição -->
            <div id="status" class="mb-4 p-3 rounded-lg hidden">
                <span id="status-text"></span>
            </div>

            <!-- Informações da API -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">🔗 Endpoint da API:</h3>
                <code class="bg-gray-200 px-2 py-1 rounded text-sm" id="api-endpoint">
                    {{ url('/api/certificates') }}
                </code>
                <p class="text-sm text-gray-600 mt-2">
                    <strong>Método:</strong> GET | <strong>Resposta:</strong> JSON
                </p>
            </div>

            <!-- Resultados -->
            <div id="results-section" class="hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 Resultados:</h2>
                
                <!-- Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600" id="total-count">-</div>
                        <div class="text-sm text-blue-600">Total de Certificados</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="text-2xl font-bold text-green-600" id="response-time">-</div>
                        <div class="text-sm text-green-600">Tempo de Resposta</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                        <div class="text-2xl font-bold text-purple-600" id="status-code">-</div>
                        <div class="text-sm text-purple-600">Status Code</div>
                    </div>
                </div>

                <!-- Lista de certificados -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Título</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Horas</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Categorias</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Professores</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Criado em</th>
                            </tr>
                        </thead>
                        <tbody id="certificates-table">
                            <!-- Os dados serão inseridos aqui via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- JSON Raw -->
            <div id="json-section" class="hidden mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">🔍 JSON Response:</h3>
                <pre id="json-raw" class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto text-sm"></pre>
            </div>
        </div>
    </div>

    <script>
        const apiEndpoint = '{{ url("/api/certificates") }}';
        
        async function loadCertificates() {
            const startTime = Date.now();
            
            showStatus('loading', '⏳ Carregando certificados...');
            
            try {
                const response = await fetch(apiEndpoint, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                const endTime = Date.now();
                const responseTime = endTime - startTime;
                
                const data = await response.json();
                
                if (response.ok) {
                    showStatus('success', `✅ ${data.message}`);
                    displayResults(data, response.status, responseTime);
                } else {
                    showStatus('error', `❌ Erro: ${data.message}`);
                }
                
            } catch (error) {
                showStatus('error', `❌ Erro de conexão: ${error.message}`);
                console.error('Erro:', error);
            }
        }
        
        function displayResults(data, statusCode, responseTime) {
            // Mostrar seção de resultados
            document.getElementById('results-section').classList.remove('hidden');
            document.getElementById('json-section').classList.remove('hidden');
            
            // Atualizar estatísticas
            document.getElementById('total-count').textContent = data.total || 0;
            document.getElementById('response-time').textContent = `${responseTime}ms`;
            document.getElementById('status-code').textContent = statusCode;
            
            // Mostrar JSON raw
            document.getElementById('json-raw').textContent = JSON.stringify(data, null, 2);
            
            // Preencher tabela
            const tableBody = document.getElementById('certificates-table');
            tableBody.innerHTML = '';
            
            if (data.data && data.data.length > 0) {
                data.data.forEach(cert => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50';
                    row.innerHTML = `
                        <td class="border border-gray-300 px-4 py-2">${cert.id}</td>
                        <td class="border border-gray-300 px-4 py-2 font-medium">${cert.title}</td>
                        <td class="border border-gray-300 px-4 py-2">${cert.quantity_hours || '-'}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            ${cert.categories.length ? cert.categories.join(', ') : 'Nenhuma'}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            ${cert.teachers.length ? cert.teachers.join(', ') : 'Nenhum'}
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">
                            ${new Date(cert.created_at).toLocaleString('pt-BR')}
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                        📭 Nenhum certificado encontrado
                    </td>
                `;
                tableBody.appendChild(row);
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
        
        function clearResults() {
            document.getElementById('results-section').classList.add('hidden');
            document.getElementById('json-section').classList.add('hidden');
            document.getElementById('status').classList.add('hidden');
        }
        
        // Carregar automaticamente ao abrir a página
        window.addEventListener('load', function() {
            console.log('Página carregada. Endpoint da API:', apiEndpoint);
        });
    </script>
</body>
</html>