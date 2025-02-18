<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Certificado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-4">
        <div class="bg-white shadow-lg rounded-lg p-6 mt-10">
            <h1 class="text-2xl font-bold text-center mb-8">Verificar Certificado</h1>
            
            <form action="{{ route('certificate_students.search') }}" method="GET" class="mb-8">
                <div class="flex gap-4">
                    <input 
                        type="text" 
                        name="code" 
                        placeholder="Digite o código do certificado" 
                        value="{{ request('code') }}"
                        class="flex-1 p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        pattern="[A-Za-z0-9]{7}"
                        title="O código deve conter 7 caracteres alfanuméricos"
                        required
                    >
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Buscar
                    </button>
                </div>
            </form>

            @if(request()->has('code') && !isset($student))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    Nenhum certificado encontrado com este código.
                </div>
            @endif

            @if(isset($student))
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <tr class="bg-gray-100">
                            <th class="text-left p-4">Nome do Aluno</th>
                            <td class="p-4">{{ $student->name ?: 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <th class="text-left p-4">Curso</th>
                            <td class="p-4">{{ $student->course ?: 'Não informado' }}</td>
                        </tr>
                        <tr class="bg-gray-100">
                            <th class="text-left p-4">Carga Horária</th>
                            <td class="p-4">{{ $student->quantity_hours ? $student->quantity_hours . ' horas' : 'Não informado' }}</td>
                        </tr>
                        <tr>
                            <th class="text-left p-4">Código do Certificado</th>
                            <td class="p-4">{{ $student->code }}</td>
                        </tr>
                        <tr class="bg-gray-100">
                            <th class="text-left p-4">Data de Início</th>
                            <td class="p-4">{{ $student->start_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-left p-4">Data de Conclusão</th>
                            <td class="p-4">{{ $student->end_date->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>
</html>