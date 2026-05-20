<template>
  <div class="space-y-8">
    <!-- Header com ações rápidas -->
    <div class="mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div class="flex items-center gap-3 mb-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/20 ring-2 ring-red-400/30">
              <Icon name="heroicons:document-text-20-solid" class="w-7 h-7 text-white" />
            </div>
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">Autorizações de Saída</h1>
              <p class="text-slate-500 dark:text-slate-400">Gerencie todas as autorizações criadas pela coordenação</p>
            </div>
          </div>
        </div>
        
        <NuxtLink to="/admin/authorizations/create">
          <button class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl transition-all duration-300 flex items-center gap-2 shadow-lg shadow-red-500/20 font-semibold">
            <Icon name="heroicons:plus-20-solid" class="w-5 h-5" />
            Nova Autorização
          </button>
        </NuxtLink>
      </div>
    </div>

    <!-- Filtros Avançados -->
    <AppCard class="overflow-hidden">
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
              <Icon name="heroicons:filter-20-solid" class="w-4 h-4 text-red-500" />
              Status
            </label>
            <select v-model="filters.status" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 transition-all">
              <option value="">Todos</option>
              <option value="pending">⏳ Pendente</option>
              <option value="approved_by_professor">✅ Aprovado</option>
              <option value="rejected">❌ Rejeitado</option>
              <option value="completed">🏁 Concluído</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
              <Icon name="heroicons:user-20-solid" class="w-4 h-4 text-red-500" />
              Aluno
            </label>
            <input v-model="filters.aluno" type="text" placeholder="Nome do aluno..." class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 transition-all" @keyup.enter="applyFilters">
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
              <Icon name="heroicons:users-20-solid" class="w-4 h-4 text-red-500" />
              Turma
            </label>
            <input v-model="filters.turma" type="text" placeholder="Turma..." class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 transition-all" @keyup.enter="applyFilters">
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
              <Icon name="heroicons:calendar-20-solid" class="w-4 h-4 text-red-500" />
              Período
            </label>
            <select v-model="filters.periodo" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 transition-all">
              <option value="">Todos</option>
              <option value="today">Hoje</option>
              <option value="yesterday">Ontem</option>
              <option value="week">Última semana</option>
              <option value="month">Último mês</option>
            </select>
          </div>
        </div>
        
        <div class="flex justify-between items-center mt-6 pt-4 border-t border-slate-200 dark:border-slate-700">
          <div class="text-sm text-slate-500 flex items-center gap-2">
            <Icon name="heroicons:information-circle-20-solid" class="w-4 h-4" />
            {{ authorizations.length }} de {{ pagination.total }} registros
          </div>
          <div class="flex gap-3">
            <button @click="resetFilters" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors flex items-center gap-2">
              <Icon name="heroicons:arrow-path-20-solid" class="w-4 h-4" />
              Limpar
            </button>
            <button @click="applyFilters" :disabled="loading" class="px-5 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl transition-all duration-300 flex items-center gap-2 shadow-md font-medium">
              <Icon v-if="loading" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
              <Icon v-else name="heroicons:magnifying-glass-20-solid" class="w-4 h-4" />
              {{ loading ? 'Buscando...' : 'Filtrar' }}
            </button>
          </div>
        </div>
      </div>
    </AppCard>

    <!-- Tabela Moderna -->
    <AppCard no-padding class="overflow-hidden">
      <div v-if="loading" class="text-center py-16">
        <div class="inline-flex flex-col items-center gap-3">
          <Icon name="heroicons:arrow-path-20-solid" class="w-10 h-10 animate-spin text-red-500" />
          <p class="text-slate-500">Carregando autorizações...</p>
        </div>
      </div>

      <div v-else-if="authorizations.length === 0" class="text-center py-16">
        <div class="inline-flex flex-col items-center gap-3">
          <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
            <Icon name="heroicons:document-text-20-solid" class="w-10 h-10 text-slate-300 dark:text-slate-600" />
          </div>
          <p class="text-slate-500 dark:text-slate-400">Nenhuma autorização encontrada</p>
          <NuxtLink to="/admin/authorizations/create" class="text-red-500 hover:text-red-600 text-sm font-medium inline-flex items-center gap-1">
            Criar primeira autorização
            <Icon name="heroicons:arrow-right-20-solid" class="w-4 h-4" />
          </NuxtLink>
        </div>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 border-b-2 border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Aluno</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Turma</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Horário</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Professor</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Data</th>
              <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="auth in authorizations" :key="auth.id" class="hover:bg-gradient-to-r hover:from-slate-50 hover:to-transparent dark:hover:from-slate-800/50 transition-all duration-300 group">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-500/20 dark:to-blue-600/20 flex items-center justify-center">
                    <Icon name="heroicons:user-20-solid" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                  </div>
                  <div>
                    <div class="font-semibold text-slate-900 dark:text-white">{{ auth.aluno_nome }}</div>
                    <div class="text-xs text-slate-400 truncate max-w-[200px]">{{ auth.motivo_saida || 'Sem motivo' }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="px-3 py-1.5 text-xs font-mono font-semibold bg-gradient-to-r from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 rounded-lg">{{ auth.turma }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="font-mono text-sm font-bold text-slate-700 dark:text-slate-300">{{ formatTime(auth.horario_saida) }}</div>
                <div class="text-xs text-slate-400">{{ auth.aula_numero }}ª Aula</div>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                    <Icon name="heroicons:academic-cap-20-solid" class="w-3 h-3 text-slate-500" />
                  </div>
                  <span class="text-slate-600 dark:text-slate-400">{{ auth.professor?.name || '—' }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span :class="statusBadge(auth.status)" class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm inline-flex items-center gap-1">
                  <span :class="statusDot(auth.status)"></span>
                  {{ statusText(auth.status) }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-slate-500">{{ formatDate(auth.created_at) }}</div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button @click="viewDetails(auth)" class="p-2 rounded-xl text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all duration-200 group" title="Ver detalhes">
                    <Icon name="heroicons:eye-20-solid" class="w-5 h-5 group-hover:scale-110 transition-transform" />
                  </button>
                  <button @click="openEditModal(auth)" class="p-2 rounded-xl text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-all duration-200 group" title="Editar" v-if="auth.status === 'pending'">
                    <Icon name="heroicons:pencil-square-20-solid" class="w-5 h-5 group-hover:scale-110 transition-transform" />
                  </button>
                  <button @click="openDeleteModal(auth)" class="p-2 rounded-xl text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all duration-200 group" title="Excluir" v-if="auth.status === 'pending'">
                    <Icon name="heroicons:trash-20-solid" class="w-5 h-5 group-hover:scale-110 transition-transform" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Paginação -->
      <div v-if="authorizations.length > 0" class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50 dark:bg-slate-800/30">
        <p class="text-sm text-slate-500">
          Mostrando <span class="font-semibold text-slate-700 dark:text-slate-300">{{ authorizations.length }}</span> de <span class="font-semibold text-slate-700 dark:text-slate-300">{{ pagination.total }}</span> registros
        </p>
        <div class="flex gap-2">
          <button @click="prevPage" :disabled="pagination.current_page <= 1" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200 flex items-center gap-2">
            <Icon name="heroicons:chevron-left-20-solid" class="w-4 h-4" />
            Anterior
          </button>
          <div class="flex gap-1">
            <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :class="[
              'w-10 h-10 rounded-xl transition-all duration-200 font-medium',
              pagination.current_page === page 
                ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-md' 
                : 'border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700'
            ]">
              {{ page }}
            </button>
          </div>
          <button @click="nextPage" :disabled="pagination.current_page >= pagination.last_page" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200 flex items-center gap-2">
            Próxima
            <Icon name="heroicons:chevron-right-20-solid" class="w-4 h-4" />
          </button>
        </div>
      </div>
    </AppCard>

    <!-- Modal de Detalhes -->
    <AppModal v-model="showDetailsModal" :title="`Detalhes da Autorização`" size="lg">
      <div v-if="selectedAuth" class="space-y-6">
        <!-- Header do Modal -->
        <div class="flex items-center gap-4 pb-4 border-b border-slate-200 dark:border-slate-700">
          <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
            <Icon name="heroicons:document-text-20-solid" class="w-8 h-8 text-white" />
          </div>
          <div class="flex-1">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ selectedAuth.aluno_nome }}</h3>
            <div class="flex items-center gap-2 mt-1">
              <span :class="statusBadge(selectedAuth.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                {{ statusText(selectedAuth.status) }}
              </span>
              <span class="text-xs text-slate-400">ID: #{{ selectedAuth.id }}</span>
            </div>
          </div>
        </div>

        <!-- Grid de Informações -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 rounded-xl">
            <p class="text-xs text-slate-500 mb-1 flex items-center gap-1">
              <Icon name="heroicons:users-20-solid" class="w-3 h-3" />
              Turma
            </p>
            <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ selectedAuth.turma }}</p>
          </div>
          
          <div class="p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 rounded-xl">
            <p class="text-xs text-slate-500 mb-1 flex items-center gap-1">
              <Icon name="heroicons:clock-20-solid" class="w-3 h-3" />
              Horário de Saída
            </p>
            <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ formatTime(selectedAuth.horario_saida) }}</p>
            <p class="text-xs text-slate-400">{{ selectedAuth.aula_numero }}ª Aula</p>
          </div>
          
          <div class="p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 rounded-xl">
            <p class="text-xs text-slate-500 mb-1 flex items-center gap-1">
              <Icon name="heroicons:academic-cap-20-solid" class="w-3 h-3" />
              Professor Responsável
            </p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.professor?.name || '-' }}</p>
            <p v-if="selectedAuth.professor?.email" class="text-xs text-slate-400">{{ selectedAuth.professor.email }}</p>
          </div>
          
          <div class="p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 rounded-xl">
            <p class="text-xs text-slate-500 mb-1 flex items-center gap-1">
              <Icon name="heroicons:user-20-solid" class="w-3 h-3" />
              Criado por
            </p>
            <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.admin?.name || '—' }}</p>
            <p class="text-xs text-slate-400">{{ formatDate(selectedAuth.created_at) }}</p>
          </div>
        </div>

        <!-- Informações Adicionais -->
        <div v-if="selectedAuth.motivo_saida" class="p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-500/10 dark:to-yellow-600/5 rounded-xl border border-yellow-200 dark:border-yellow-500/20">
          <p class="text-xs text-yellow-700 dark:text-yellow-400 mb-1 flex items-center gap-1">
            <Icon name="heroicons:information-circle-20-solid" class="w-3 h-3" />
            Motivo da Saída
          </p>
          <p class="text-slate-700 dark:text-slate-300">{{ selectedAuth.motivo_saida }}</p>
        </div>

        <div v-if="selectedAuth.observacoes" class="p-4 bg-slate-50 dark:bg-slate-800/30 rounded-xl">
          <p class="text-xs text-slate-500 mb-1 flex items-center gap-1">
            <Icon name="heroicons:chat-bubble-left-20-solid" class="w-3 h-3" />
            Observações
          </p>
          <p class="text-slate-700 dark:text-slate-300">{{ selectedAuth.observacoes }}</p>
        </div>

        <!-- Validação do Professor -->
        <div v-if="selectedAuth.validation" class="p-4 rounded-xl border" :class="selectedAuth.validation.status === 'approved' ? 'border-green-200 dark:border-green-500/20 bg-green-50 dark:bg-green-500/5' : 'border-red-200 dark:border-red-500/20 bg-red-50 dark:bg-red-500/5'">
          <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-semibold uppercase flex items-center gap-1" :class="selectedAuth.validation.status === 'approved' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
              <Icon :name="selectedAuth.validation.status === 'approved' ? 'heroicons:check-badge-20-solid' : 'heroicons:x-circle-20-solid'" class="w-4 h-4" />
              {{ selectedAuth.validation.status === 'approved' ? 'APROVADA PELO PROFESSOR' : 'REJEITADA PELO PROFESSOR' }}
            </p>
            <span class="text-xs text-slate-400">{{ formatDate(selectedAuth.validation.validated_at) }}</span>
          </div>
          <p v-if="selectedAuth.validation.com_falta" class="text-sm text-amber-600 dark:text-amber-400 flex items-center gap-1">
            <Icon name="heroicons:exclamation-triangle-20-solid" class="w-4 h-4" />
            Aluno receberá falta
          </p>
          <p v-if="selectedAuth.validation.observacao" class="text-sm text-slate-600 dark:text-slate-400 mt-2">{{ selectedAuth.validation.observacao }}</p>
        </div>

        <!-- Ações do Modal -->
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
          <button @click="showDetailsModal = false" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Fechar
          </button>
          <button v-if="selectedAuth.status === 'pending'" @click="openEditModal(selectedAuth); showDetailsModal = false" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white transition-colors flex items-center gap-2">
            <Icon name="heroicons:pencil-square-20-solid" class="w-4 h-4" />
            Editar
          </button>
          <button v-if="selectedAuth.status === 'pending'" @click="openDeleteModal(selectedAuth); showDetailsModal = false" class="px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white transition-colors flex items-center gap-2">
            <Icon name="heroicons:trash-20-solid" class="w-4 h-4" />
            Excluir
          </button>
        </div>
      </div>
    </AppModal>

    <!-- Modal de Edição -->
    <AppModal v-model="showEditModal" title="Editar Autorização" size="lg">
      <form @submit.prevent="submitEdit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nome do Aluno *</label>
            <input v-model="editForm.aluno_nome" type="text" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Turma *</label>
            <input v-model="editForm.turma" type="text" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Horário de Saída *</label>
            <input v-model="editForm.horario_saida" type="time" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nº da Aula *</label>
            <select v-model="editForm.aula_numero" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
              <option value="">Selecione</option>
              <option v-for="n in 5" :key="n" :value="n">{{ n }}ª Aula</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Motivo da Saída</label>
            <textarea v-model="editForm.motivo_saida" rows="2" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" placeholder="Informe o motivo da saída (opcional)"></textarea>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Observações</label>
            <textarea v-model="editForm.observacoes" rows="2" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" placeholder="Informações adicionais (opcional)"></textarea>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
          <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-medium">
            Cancelar
          </button>
          <button type="submit" :disabled="loadingEdit" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-xl transition-all duration-300 flex items-center gap-2 font-medium shadow-md">
            <Icon v-if="loadingEdit" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
            <Icon v-else name="heroicons:pencil-square-20-solid" class="w-4 h-4" />
            {{ loadingEdit ? 'Salvando...' : 'Salvar Alterações' }}
          </button>
        </div>
      </form>
    </AppModal>

    <!-- Modal de Confirmação de Exclusão -->
    <AppModal v-model="showDeleteModal" title="Confirmar Exclusão" size="sm">
      <div class="text-center">
        <div class="w-20 h-20 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center mx-auto mb-4">
          <Icon name="heroicons:trash-20-solid" class="w-10 h-10 text-red-500" />
        </div>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Excluir Autorização</h3>
        <p class="text-slate-500 dark:text-slate-400 mb-6">
          Tem certeza que deseja excluir a autorização de <strong class="text-slate-900 dark:text-white">{{ deleteAuth?.aluno_nome }}</strong>?<br>
          Esta ação não pode ser desfeita.
        </p>
        <div class="flex gap-3">
          <button @click="showDeleteModal = false" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-medium">
            Cancelar
          </button>
          <button @click="confirmDelete" :disabled="loadingDelete" class="flex-1 px-4 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white transition-all duration-300 flex items-center justify-center gap-2 font-medium">
            <Icon v-if="loadingDelete" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
            <Icon v-else name="heroicons:trash-20-solid" class="w-4 h-4" />
            {{ loadingDelete ? 'Excluindo...' : 'Confirmar Exclusão' }}
          </button>
        </div>
      </div>
    </AppModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthorizations } from '~/composables/useAuthorizations'
import { useToast } from '~/composables/useToast'
import AppCard from '~/components/common/AppCard.vue'
import AppModal from '~/components/common/AppModal.vue'

definePageMeta({
  middleware: 'auth',
  layout: 'admin'
})

const route = useRoute()
const router = useRouter()
const { authorizations, loading, pagination, fetchAll, update, cancel } = useAuthorizations()
const { show: toast } = useToast()

const filters = ref({
  status: route.query.status || '',
  aluno: '',
  turma: '',
  periodo: ''
})

const selectedAuth = ref(null)
const showDetailsModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const deleteAuth = ref(null)
const loadingEdit = ref(false)
const loadingDelete = ref(false)

const editForm = ref({
  id: null,
  aluno_nome: '',
  turma: '',
  motivo_saida: '',
  horario_saida: '',
  aula_numero: '',
  observacoes: ''
})

const visiblePages = computed(() => {
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const delta = 2
  const range = []
  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    range.push(i)
  }
  if (current - delta > 2) range.unshift('...')
  if (current + delta < last - 1) range.push('...')
  range.unshift(1)
  if (last !== 1 && last !== undefined) range.push(last)
  return range
})

const loadData = async () => {
  const params = { ...filters.value }
  if (filters.value.periodo) {
    const today = new Date()
    switch (filters.value.periodo) {
      case 'today':
        params.data_inicio = today.toISOString().split('T')[0]
        break
      case 'yesterday':
        const yesterday = new Date(today)
        yesterday.setDate(today.getDate() - 1)
        params.data_inicio = yesterday.toISOString().split('T')[0]
        break
      case 'week':
        const weekAgo = new Date(today)
        weekAgo.setDate(today.getDate() - 7)
        params.data_inicio = weekAgo.toISOString().split('T')[0]
        break
      case 'month':
        const monthAgo = new Date(today)
        monthAgo.setMonth(today.getMonth() - 1)
        params.data_inicio = monthAgo.toISOString().split('T')[0]
        break
    }
  }
  Object.keys(params).forEach(key => {
    if (!params[key] || params[key] === '') delete params[key]
  })
  await fetchAll(params)
}

const applyFilters = () => {
  loadData()
}

const resetFilters = () => {
  filters.value = { status: '', aluno: '', turma: '', periodo: '' }
  loadData()
}

const goToPage = (page) => {
  if (page !== '...') {
    fetchAll({ page, ...filters.value })
  }
}

const prevPage = () => {
  if (pagination.value.current_page > 1) {
    fetchAll({ page: pagination.value.current_page - 1, ...filters.value })
  }
}

const nextPage = () => {
  if (pagination.value.current_page < pagination.value.last_page) {
    fetchAll({ page: pagination.value.current_page + 1, ...filters.value })
  }
}

const viewDetails = (auth) => {
  selectedAuth.value = auth
  showDetailsModal.value = true
}

const openEditModal = (auth) => {
  editForm.value = {
    id: auth.id,
    aluno_nome: auth.aluno_nome,
    turma: auth.turma,
    motivo_saida: auth.motivo_saida || '',
    horario_saida: auth.horario_saida?.substring(0, 5) || '',
    aula_numero: auth.aula_numero,
    observacoes: auth.observacoes || ''
  }
  showEditModal.value = true
}

const submitEdit = async () => {
  if (!editForm.value.aluno_nome || !editForm.value.turma || !editForm.value.horario_saida || !editForm.value.aula_numero) {
    toast('Preencha todos os campos obrigatórios', 'error')
    return
  }
  
  loadingEdit.value = true
  try {
    await update(editForm.value.id, {
      aluno_nome: editForm.value.aluno_nome,
      turma: editForm.value.turma,
      motivo_saida: editForm.value.motivo_saida,
      horario_saida: editForm.value.horario_saida,
      aula_numero: editForm.value.aula_numero,
      observacoes: editForm.value.observacoes
    })
    toast('Autorização atualizada com sucesso!', 'success')
    showEditModal.value = false
    await loadData()
  } catch (error) {
    console.error('Erro ao editar autorização:', error)
    toast('Erro ao atualizar autorização', 'error')
  } finally {
    loadingEdit.value = false
  }
}

const openDeleteModal = (auth) => {
  deleteAuth.value = auth
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteAuth.value) return
  
  loadingDelete.value = true
  try {
    await cancel(deleteAuth.value.id)
    toast('Autorização excluída com sucesso!', 'success')
    showDeleteModal.value = false
    deleteAuth.value = null
    await loadData()
  } catch (error) {
    console.error('Erro ao excluir autorização:', error)
    toast('Erro ao excluir autorização', 'error')
  } finally {
    loadingDelete.value = false
  }
}

const statusBadge = (status) => {
  const badges = {
    pending: 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-900 shadow-yellow-500/20',
    approved_by_professor: 'bg-gradient-to-r from-green-400 to-green-500 text-green-900 shadow-green-500/20',
    rejected: 'bg-gradient-to-r from-red-400 to-red-500 text-red-900 shadow-red-500/20',
    completed: 'bg-gradient-to-r from-blue-400 to-blue-500 text-blue-900 shadow-blue-500/20'
  }
  return badges[status] || 'bg-gray-100 text-gray-700'
}

const statusDot = (status) => {
  const dots = {
    pending: 'bg-yellow-900',
    approved_by_professor: 'bg-green-900',
    rejected: 'bg-red-900',
    completed: 'bg-blue-900'
  }
  return `w-1.5 h-1.5 rounded-full ${dots[status] || 'bg-gray-900'}`
}

const statusText = (status) => {
  const texts = {
    pending: 'Pendente',
    approved_by_professor: 'Aprovado',
    rejected: 'Rejeitado',
    completed: 'Concluído'
  }
  return texts[status] || status
}

const formatTime = (time) => {
  if (!time) return '—'
  return time.substring(0, 5)
}

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' })
}

onMounted(() => {
  loadData()
})
</script>