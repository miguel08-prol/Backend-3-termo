<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-md">
            <Icon name="heroicons:document-text-20-solid" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Autorizações</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Gerencie todas as autorizações de saída/entrada</p>
          </div>
        </div>
      </div>
      
      <NuxtLink to="/admin/authorizations/create">
        <button class="px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors flex items-center gap-2 font-medium shadow-sm">
          <Icon name="heroicons:plus-20-solid" class="w-5 h-5" />
          Nova Autorização
        </button>
      </NuxtLink>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Status</label>
          <select v-model="filters.status" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-red-500">
            <option value="">Todos</option>
            <option value="approved_by_professor">✅ Autorizada</option>
            <option value="completed">🏁 Concluída</option>
            <option value="cancelled">❌ Cancelada</option>
          </select>
        </div>
        
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Aluno</label>
          <input v-model="filters.aluno" type="text" placeholder="Nome do aluno..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-red-500">
        </div>
        
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Turma</label>
          <input v-model="filters.turma" type="text" placeholder="Turma..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-red-500">
        </div>
        
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Tipo</label>
          <select v-model="filters.tipo" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-red-500">
            <option value="">Todos</option>
            <option value="saida">🚪 Saída</option>
            <option value="entrada">🏠 Entrada</option>
          </select>
        </div>
        
        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Período</label>
          <select v-model="filters.periodo" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-red-500">
            <option value="">Todos</option>
            <option value="today">Hoje</option>
            <option value="yesterday">Ontem</option>
            <option value="week">Última semana</option>
            <option value="month">Último mês</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Com Falta</label>
          <select v-model="filters.com_falta" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-red-500">
            <option value="">Todos</option>
            <option value="1">⚠️ Sim (gerou falta)</option>
            <option value="0">✅ Não (não gerou falta)</option>
          </select>
        </div>
      </div>
      
      <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
        <button @click="resetFilters" class="px-3 py-1.5 text-sm text-slate-500 hover:text-slate-700 transition-colors">
          Limpar
        </button>
        <button @click="applyFilters" :disabled="loading" class="px-4 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition-colors flex items-center gap-1">
          <Icon v-if="loading" name="heroicons:arrow-path-20-solid" class="w-3 h-3 animate-spin" />
          <Icon v-else name="heroicons:magnifying-glass-20-solid" class="w-3 h-3" />
          Filtrar
        </button>
      </div>
    </div>

    <!-- Cards Grid -->
    <div v-if="loading && authorizations.length === 0" class="flex justify-center py-16">
      <div class="flex flex-col items-center gap-3">
        <Icon name="heroicons:arrow-path-20-solid" class="w-8 h-8 animate-spin text-red-500" />
        <p class="text-slate-500">Carregando...</p>
      </div>
    </div>

    <div v-else-if="authorizations.length === 0" class="text-center py-16 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
      <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3">
        <Icon name="heroicons:document-text-20-solid" class="w-8 h-8 text-slate-300 dark:text-slate-600" />
      </div>
      <p class="text-slate-500">Nenhuma autorização encontrada</p>
      <NuxtLink to="/admin/authorizations/create" class="text-red-500 text-sm mt-2 inline-block">
        Criar primeira autorização
      </NuxtLink>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div 
        v-for="auth in authorizations" 
        :key="auth.id"
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden hover:shadow-md transition-all duration-200 group"
      >
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div :class="[
              'w-8 h-8 rounded-lg flex items-center justify-center',
              auth.tipo === 'entrada' ? 'bg-emerald-100 dark:bg-emerald-500/20' : 'bg-red-100 dark:bg-red-500/20'
            ]">
              <Icon :name="auth.tipo === 'entrada' ? 'heroicons:arrow-left-start-on-rectangle-20-solid' : 'heroicons:arrow-right-start-on-rectangle-20-solid'" 
                    :class="auth.tipo === 'entrada' ? 'text-emerald-600' : 'text-red-600'" 
                    class="w-4 h-4" />
            </div>
            <span class="text-xs font-medium text-slate-400">#{{ auth.id }}</span>
          </div>
          <div class="flex items-center gap-2">
            <span v-if="auth.com_falta" class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
              ⚠️ Falta
            </span>
            <span :class="statusBadge(auth.status)" class="px-2 py-0.5 text-xs font-medium rounded-full">
              {{ statusText(auth.status) }}
            </span>
          </div>
        </div>
        
        <div class="p-4">
          <div class="flex items-start gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-500/20 dark:to-blue-600/20 flex items-center justify-center flex-shrink-0">
              <Icon name="heroicons:user-20-solid" class="w-5 h-5 text-blue-600" />
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="font-bold text-slate-900 dark:text-white truncate">{{ auth.aluno_nome }}</h3>
              <p class="text-xs text-slate-400 truncate">{{ auth.motivo_saida || 'Sem motivo informado' }}</p>
            </div>
          </div>
          
          <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
              <p class="text-xs text-slate-400">Turma</p>
              <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ auth.turma }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-400">Horário</p>
              <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ formatTime(auth.horario_saida) }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-400">Aula</p>
              <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ auth.aula_numero ? auth.aula_numero + 'ª' : '-' }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-400">Professor</p>
              <p class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ auth.professor?.name || '-' }}</p>
            </div>
          </div>
          
          <div class="flex items-center gap-1 text-xs text-slate-400 mb-3">
            <Icon name="heroicons:calendar-20-solid" class="w-3 h-3" />
            {{ formatDate(auth.created_at) }}
          </div>
        </div>
        
        <div class="p-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-2">
          <button @click="viewDetails(auth)" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors" title="Ver detalhes">
            <Icon name="heroicons:eye-20-solid" class="w-4 h-4" />
          </button>
          <button @click="openEditModal(auth)" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-colors" title="Editar">
            <Icon name="heroicons:pencil-square-20-solid" class="w-4 h-4" />
          </button>
          <button @click="openDeleteModal(auth)" class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors" title="Excluir">
            <Icon name="heroicons:trash-20-solid" class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Paginação -->
    <div v-if="pagination.total > 0" class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-4">
      <p class="text-sm text-slate-500">
        Mostrando <span class="font-medium">{{ authorizations.length }}</span> de <span class="font-medium">{{ pagination.total }}</span> registros
      </p>
      <div class="flex gap-1">
        <button @click="prevPage" :disabled="pagination.current_page <= 1" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center justify-center">
          <Icon name="heroicons:chevron-left-20-solid" class="w-4 h-4" />
        </button>
        <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :class="[
          'w-8 h-8 rounded-lg transition-colors text-sm font-medium',
          pagination.current_page === page 
            ? 'bg-red-500 text-white' 
            : 'border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800'
        ]">
          {{ page }}
        </button>
        <button @click="nextPage" :disabled="pagination.current_page >= pagination.last_page" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center justify-center">
          <Icon name="heroicons:chevron-right-20-solid" class="w-4 h-4" />
        </button>
      </div>
    </div>

    <!-- MODAL DE DETALHES COM ABAS -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeDetailsModal">
          <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
          <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
            <div v-if="selectedAuth">
              <!-- Header -->
              <div class="bg-gradient-to-r from-red-500 to-red-600 p-5 text-white">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div :class="[
                      'w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center',
                      selectedAuth.tipo === 'entrada' ? 'bg-emerald-500/30' : ''
                    ]">
                      <Icon :name="selectedAuth.tipo === 'entrada' ? 'heroicons:arrow-left-start-on-rectangle-20-solid' : 'heroicons:arrow-right-start-on-rectangle-20-solid'" class="w-6 h-6" />
                    </div>
                    <div>
                      <h3 class="text-xl font-bold">{{ selectedAuth.aluno_nome }}</h3>
                      <p class="text-red-100 text-xs">ID: #{{ selectedAuth.id }}</p>
                    </div>
                  </div>
                  <button @click="closeDetailsModal" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 transition-colors flex items-center justify-center">
                    <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5" />
                  </button>
                </div>
              </div>

              <!-- Abas -->
              <div class="border-b border-slate-200 dark:border-slate-700">
                <div class="flex px-5 gap-1">
                  <button 
                    @click="activeTab = 'info'"
                    :class="[
                      'px-4 py-3 text-sm font-medium transition-all duration-200 border-b-2',
                      activeTab === 'info' 
                        ? 'border-red-500 text-red-600 dark:text-red-400' 
                        : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'
                    ]"
                  >
                    <Icon name="heroicons:information-circle-20-solid" class="w-4 h-4 inline mr-2" />
                    Informações
                  </button>
                  <button 
                    @click="activeTab = 'faltas'"
                    :class="[
                      'px-4 py-3 text-sm font-medium transition-all duration-200 border-b-2',
                      activeTab === 'faltas' 
                        ? 'border-red-500 text-red-600 dark:text-red-400' 
                        : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'
                    ]"
                  >
                    <Icon name="heroicons:exclamation-triangle-20-solid" class="w-4 h-4 inline mr-2" />
                    Faltas
                  </button>
                  <button 
                    @click="activeTab = 'logs'"
                    :class="[
                      'px-4 py-3 text-sm font-medium transition-all duration-200 border-b-2',
                      activeTab === 'logs' 
                        ? 'border-red-500 text-red-600 dark:text-red-400' 
                        : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'
                    ]"
                  >
                    <Icon name="heroicons:document-text-20-solid" class="w-4 h-4 inline mr-2" />
                    Notificações
                  </button>
                </div>
              </div>

              <!-- Conteúdo das Abas -->
              <div class="overflow-y-auto" style="max-height: calc(90vh - 180px)">
                <!-- Aba Informações -->
                <div v-show="activeTab === 'info'" class="p-5 space-y-4">
                  <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                      <p class="text-xs text-slate-500">Turma</p>
                      <p class="font-semibold text-slate-900 dark:text-white">{{ selectedAuth.turma }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                      <p class="text-xs text-slate-500">Turno</p>
                      <p class="font-semibold capitalize">{{ selectedAuth.turno || 'Não definido' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                      <p class="text-xs text-slate-500">Tipo</p>
                      <p class="font-semibold capitalize" :class="selectedAuth.tipo === 'entrada' ? 'text-emerald-600' : 'text-red-600'">
                        {{ selectedAuth.tipo === 'entrada' ? 'Entrada' : 'Saída' }}
                      </p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                      <p class="text-xs text-slate-500">Horário</p>
                      <p class="font-semibold font-mono">{{ formatTime(selectedAuth.horario_saida) }}</p>
                      <p class="text-xs text-slate-400">{{ selectedAuth.aula_numero ? selectedAuth.aula_numero + 'ª Aula' : 'Horário livre' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                      <p class="text-xs text-slate-500">Professor</p>
                      <p class="font-semibold">{{ selectedAuth.professor?.name || '-' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                      <p class="text-xs text-slate-500">Criado por</p>
                      <p class="font-semibold">{{ selectedAuth.admin?.name || '-' }}</p>
                    </div>
                  </div>

                  <div v-if="selectedAuth.motivo_saida" class="bg-yellow-50 dark:bg-yellow-500/10 rounded-lg p-3 border border-yellow-200 dark:border-yellow-500/20">
                    <p class="text-xs text-yellow-700 dark:text-yellow-400 flex items-center gap-1">
                      <Icon name="heroicons:chat-bubble-left-20-solid" class="w-3 h-3" />
                      Motivo
                    </p>
                    <p class="text-sm">{{ selectedAuth.motivo_saida }}</p>
                  </div>

                  <div v-if="selectedAuth.observacoes" class="bg-slate-50 dark:bg-slate-800/30 rounded-lg p-3">
                    <p class="text-xs text-slate-500 flex items-center gap-1">
                      <Icon name="heroicons:document-text-20-solid" class="w-3 h-3" />
                      Observações
                    </p>
                    <p class="text-sm">{{ selectedAuth.observacoes }}</p>
                  </div>

                  <div class="flex justify-between text-xs text-slate-400 border-t border-slate-100 dark:border-slate-800 pt-3">
                    <span>Criado em: {{ formatDateTime(selectedAuth.created_at) }}</span>
                    <span v-if="selectedAuth.autorizado_em">Autorizado em: {{ formatDateTime(selectedAuth.autorizado_em) }}</span>
                  </div>
                </div>

                <!-- Aba Faltas -->
                <div v-show="activeTab === 'faltas'" class="p-5">
                  <div v-if="selectedAuth.com_falta" class="bg-amber-50 dark:bg-amber-500/10 rounded-xl p-4 border border-amber-200 dark:border-amber-500/20 mb-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-full bg-amber-200 dark:bg-amber-500/30 flex items-center justify-center">
                        <Icon name="heroicons:exclamation-triangle-20-solid" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                      </div>
                      <div>
                        <p class="font-semibold text-amber-700 dark:text-amber-400">Esta autorização gerou falta(s)</p>
                        <p class="text-sm text-amber-600 dark:text-amber-500">O aluno teve falta registrada</p>
                      </div>
                    </div>
                  </div>
                  <div v-else class="bg-green-50 dark:bg-green-500/10 rounded-xl p-4 border border-green-200 dark:border-green-500/20 mb-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-full bg-green-200 dark:bg-green-500/30 flex items-center justify-center">
                        <Icon name="heroicons:check-badge-20-solid" class="w-5 h-5 text-green-600 dark:text-green-400" />
                      </div>
                      <div>
                        <p class="font-semibold text-green-700 dark:text-green-400">Esta autorização NÃO gerou falta</p>
                        <p class="text-sm text-green-600 dark:text-green-500">Saída/Entrada justificada</p>
                      </div>
                    </div>
                  </div>

                  <!-- Componente de faltas do aluno -->
                  <FaltasCard :aluno-id="alunoId" :aluno-nome="selectedAuth.aluno_nome" />
                </div>

                <!-- Aba Logs de Notificações -->
                <div v-show="activeTab === 'logs'" class="p-5">
                  <div v-if="notificationLogs.length === 0" class="text-center py-8">
                    <Icon name="heroicons:inbox-20-solid" class="w-12 h-12 text-slate-300 mx-auto mb-3" />
                    <p class="text-slate-500">Nenhuma notificação registrada</p>
                  </div>
                  <div v-else class="space-y-3">
                    <div v-for="log in notificationLogs" :key="log.id" class="flex items-start gap-3 p-3 bg-slate-50 dark:bg-slate-800/30 rounded-lg">
                      <div :class="[
                        'w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0',
                        log.tipo === 'email' ? 'bg-blue-100 dark:bg-blue-500/20' : 'bg-green-100 dark:bg-green-500/20'
                      ]">
                        <Icon :name="log.tipo === 'email' ? 'heroicons:envelope-20-solid' : 'heroicons:device-phone-mobile-20-solid'" class="w-4 h-4" :class="log.tipo === 'email' ? 'text-blue-600' : 'text-green-600'" />
                      </div>
                      <div class="flex-1">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                          <p class="text-sm font-medium text-slate-900 dark:text-white">
                            {{ log.tipo === 'email' ? 'E-mail' : 'WhatsApp' }}
                          </p>
                          <span :class="log.status === 'sent' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'" class="text-xs px-2 py-0.5 rounded-full">
                            {{ log.status === 'sent' ? 'Enviado' : 'Simulado' }}
                          </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Destinatário: {{ log.destinatario }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ formatDateTime(log.created_at) }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Footer -->
              <div class="border-t border-slate-100 dark:border-slate-800 p-4 bg-slate-50 dark:bg-slate-800/30 flex justify-end gap-2">
                <button @click="closeDetailsModal" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 transition-colors text-sm font-medium">
                  Fechar
                </button>
                <button @click="openEditModalFromDetails" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition-colors flex items-center gap-1 text-sm font-medium">
                  <Icon name="heroicons:pencil-square-20-solid" class="w-4 h-4" />
                  Editar
                </button>
                <button @click="openDeleteModalFromDetails" class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors flex items-center gap-1 text-sm font-medium">
                  <Icon name="heroicons:trash-20-solid" class="w-4 h-4" />
                  Excluir
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- MODAL DE EDIÇÃO COM ABAS -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeEditModal">
          <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
          <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-5 text-white">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <Icon name="heroicons:pencil-square-20-solid" class="w-5 h-5" />
                  </div>
                  <div>
                    <h2 class="text-xl font-bold">Editar Autorização</h2>
                    <p class="text-amber-100 text-xs">Altere as informações da autorização</p>
                  </div>
                </div>
                <button @click="closeEditModal" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 transition-colors flex items-center justify-center">
                  <Icon name="heroicons:x-mark-20-solid" class="w-5 h-5" />
                </button>
              </div>
            </div>

            <!-- Abas -->
            <div class="border-b border-slate-200 dark:border-slate-700">
              <div class="flex px-5 gap-1">
                <button 
                  @click="editActiveTab = 'dados'"
                  :class="[
                    'px-4 py-3 text-sm font-medium transition-all duration-200 border-b-2',
                    editActiveTab === 'dados' 
                      ? 'border-amber-500 text-amber-600 dark:text-amber-400' 
                      : 'border-transparent text-slate-500 hover:text-slate-700'
                  ]"
                >
                  <Icon name="heroicons:user-20-solid" class="w-4 h-4 inline mr-2" />
                  Dados da Autorização
                </button>
                <button 
                  @click="editActiveTab = 'config'"
                  :class="[
                    'px-4 py-3 text-sm font-medium transition-all duration-200 border-b-2',
                    editActiveTab === 'config' 
                      ? 'border-amber-500 text-amber-600 dark:text-amber-400' 
                      : 'border-transparent text-slate-500 hover:text-slate-700'
                  ]"
                >
                  <Icon name="heroicons:cog-8-tooth-20-solid" class="w-4 h-4 inline mr-2" />
                  Configurações
                </button>
              </div>
            </div>

            <!-- Formulário -->
            <form @submit.prevent="submitEdit" class="overflow-y-auto" style="max-height: calc(90vh - 180px)">
              <div class="p-5 space-y-4">
                <!-- Aba Dados da Autorização -->
                <div v-show="editActiveTab === 'dados'" class="space-y-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                      Nome do Aluno <span class="text-red-500">*</span>
                    </label>
                    <input v-model="editForm.aluno_nome" type="text" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-amber-500">
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                      Turma <span class="text-red-500">*</span>
                    </label>
                    <input v-model="editForm.turma" type="text" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-amber-500">
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                      Turno
                    </label>
                    <select v-model="editForm.turno" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                      <option value="manha">🌅 Manhã</option>
                      <option value="tarde">☀️ Tarde</option>
                      <option value="noite">🌙 Noite</option>
                    </select>
                  </div>

                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Horário <span class="text-red-500">*</span>
                      </label>
                      <input v-model="editForm.horario_saida" type="time" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                      <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Nº da Aula
                      </label>
                      <select v-model="editForm.aula_numero" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                        <option :value="null">Não se aplica</option>
                        <option v-for="n in 5" :key="n" :value="n">{{ n }}ª Aula</option>
                      </select>
                      <p class="text-xs text-slate-400 mt-1">Informe apenas se for saída/entrada em aula específica</p>
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Motivo</label>
                    <textarea v-model="editForm.motivo_saida" rows="2" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg resize-none" placeholder="Opcional"></textarea>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Observações</label>
                    <textarea v-model="editForm.observacoes" rows="2" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg resize-none" placeholder="Opcional"></textarea>
                  </div>
                </div>

                <!-- Aba Configurações -->
                <div v-show="editActiveTab === 'config'" class="space-y-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Tipo de Movimentação</label>
                    <div class="grid grid-cols-2 gap-2">
                      <label :class="[
                        'flex items-center justify-center gap-2 p-3 rounded-lg border cursor-pointer transition-all',
                        editForm.tipo === 'saida' ? 'border-red-500 bg-red-50 dark:bg-red-500/10 text-red-600' : 'border-slate-200 dark:border-slate-700'
                      ]">
                        <Icon name="heroicons:arrow-right-start-on-rectangle-20-solid" class="w-4 h-4" />
                        <input type="radio" value="saida" v-model="editForm.tipo" class="hidden" />
                        <span>Saída</span>
                      </label>
                      <label :class="[
                        'flex items-center justify-center gap-2 p-3 rounded-lg border cursor-pointer transition-all',
                        editForm.tipo === 'entrada' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600' : 'border-slate-200 dark:border-slate-700'
                      ]">
                        <Icon name="heroicons:arrow-left-start-on-rectangle-20-solid" class="w-4 h-4" />
                        <input type="radio" value="entrada" v-model="editForm.tipo" class="hidden" />
                        <span>Entrada</span>
                      </label>
                    </div>
                  </div>

                  <div class="pt-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                      <div class="flex items-center gap-2">
                        <Icon name="heroicons:exclamation-triangle-20-solid" class="w-4 h-4 text-amber-500" />
                        Gerar Falta?
                      </div>
                    </label>
                    <div class="flex items-center gap-6">
                      <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" value="true" v-model="editForm.com_falta" class="w-4 h-4 text-amber-500">
                        <span class="text-sm">⚠️ Sim, gerar falta</span>
                      </label>
                      <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" value="false" v-model="editForm.com_falta" class="w-4 h-4 text-green-500">
                        <span class="text-sm">✅ Não gerar falta</span>
                      </label>
                    </div>
                    <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                      <Icon name="heroicons:information-circle-20-solid" class="w-3 h-3" />
                      Se marcado como falta, o aluno terá falta registrada automaticamente
                    </p>
                  </div>

                  <div class="pt-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Status</label>
                    <select v-model="editForm.status" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                      <option value="approved_by_professor">✅ Autorizada</option>
                      <option value="completed">🏁 Concluída</option>
                      <option value="cancelled">❌ Cancelada</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Botões -->
              <div class="border-t border-slate-100 dark:border-slate-800 p-4 bg-slate-50 dark:bg-slate-800/30 flex justify-end gap-2">
                <button type="button" @click="closeEditModal" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 transition-colors text-sm font-medium">
                  Cancelar
                </button>
                <button type="submit" :disabled="loadingEdit" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition-all flex items-center gap-1 text-sm font-medium">
                  <Icon v-if="loadingEdit" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
                  <Icon v-else name="heroicons:check-20-solid" class="w-4 h-4" />
                  {{ loadingEdit ? 'Salvando...' : 'Salvar' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- MODAL DE EXCLUSÃO -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeDeleteModal">
          <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
          <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full">
            <div class="bg-gradient-to-r from-red-500 to-red-600 p-5 text-white rounded-t-2xl">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                  <Icon name="heroicons:exclamation-triangle-20-solid" class="w-5 h-5" />
                </div>
                <div>
                  <h2 class="text-xl font-bold">Confirmar Exclusão</h2>
                  <p class="text-red-100 text-xs">Esta ação não pode ser desfeita</p>
                </div>
              </div>
            </div>

            <div class="p-6 text-center">
              <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center mx-auto mb-4">
                <Icon name="heroicons:trash-20-solid" class="w-8 h-8 text-red-500" />
              </div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Excluir Autorização</h3>
              <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">
                Tem certeza que deseja excluir a autorização de:
              </p>
              <p class="font-bold text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 py-2 px-4 rounded-lg inline-block">
                {{ deleteAuth?.aluno_nome }}
              </p>
              <p class="text-xs text-slate-400 mt-3">
                Turma: {{ deleteAuth?.turma }} | {{ deleteAuth?.aula_numero ? deleteAuth.aula_numero + 'ª Aula' : 'Horário livre' }}
              </p>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-800 p-5 bg-slate-50 dark:bg-slate-800/30 rounded-b-2xl flex justify-end gap-2">
              <button @click="closeDeleteModal" class="flex-1 px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 transition-colors text-sm font-medium">
                Cancelar
              </button>
              <button @click="confirmDelete" :disabled="loadingDelete" class="flex-1 px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-all flex items-center justify-center gap-1 text-sm font-medium">
                <Icon v-if="loadingDelete" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
                <Icon v-else name="heroicons:check-20-solid" class="w-4 h-4" />
                {{ loadingDelete ? 'Excluindo...' : 'Confirmar' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthorizations } from '~/composables/useAuthorizations'
import { useToast } from '~/composables/useToast'
import FaltasCard from '~/components/student/FaltasCard.vue'

definePageMeta({
  middleware: 'auth',
  layout: 'admin'
})

const route = useRoute()
const { authorizations, loading, pagination, fetchAll, update, cancel } = useAuthorizations()
const { show: toast } = useToast()

const filters = ref({
  status: route.query.status || '',
  aluno: '',
  turma: '',
  tipo: '',
  periodo: '',
  com_falta: ''
})

const selectedAuth = ref(null)
const showDetailsModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const deleteAuth = ref(null)
const loadingEdit = ref(false)
const loadingDelete = ref(false)
const activeTab = ref('info')
const editActiveTab = ref('dados')
const notificationLogs = ref([])

const alunoId = computed(() => null) // Ajuste conforme sua lógica

const editForm = ref({
  id: null,
  aluno_nome: '',
  turma: '',
  turno: 'manha',
  motivo_saida: '',
  horario_saida: '',
  aula_numero: null,
  tipo: 'saida',
  com_falta: 'false',
  status: 'approved_by_professor',
  observacoes: ''
})

const visiblePages = computed(() => {
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const delta = 1
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

const applyFilters = () => loadData()
const resetFilters = () => {
  filters.value = { status: '', aluno: '', turma: '', tipo: '', periodo: '', com_falta: '' }
  loadData()
}

const goToPage = (page) => {
  if (page !== '...') fetchAll({ page, ...filters.value })
}
const prevPage = () => {
  if (pagination.value.current_page > 1) fetchAll({ page: pagination.value.current_page - 1, ...filters.value })
}
const nextPage = () => {
  if (pagination.value.current_page < pagination.value.last_page) fetchAll({ page: pagination.value.current_page + 1, ...filters.value })
}

const viewDetails = (auth) => {
  selectedAuth.value = auth
  activeTab.value = 'info'
  showDetailsModal.value = true
}

const closeDetailsModal = () => {
  showDetailsModal.value = false
  selectedAuth.value = null
}

const openEditModal = (auth) => {
  editForm.value = {
    id: auth.id,
    aluno_nome: auth.aluno_nome,
    turma: auth.turma,
    turno: auth.turno || 'manha',
    motivo_saida: auth.motivo_saida || '',
    horario_saida: auth.horario_saida?.substring(0, 5) || '',
    aula_numero: auth.aula_numero || null,
    tipo: auth.tipo || 'saida',
    com_falta: auth.com_falta ? 'true' : 'false',
    status: auth.status || 'approved_by_professor',
    observacoes: auth.observacoes || ''
  }
  editActiveTab.value = 'dados'
  showEditModal.value = true
}

const openEditModalFromDetails = () => {
  if (selectedAuth.value) {
    openEditModal(selectedAuth.value)
    closeDetailsModal()
  }
}

const closeEditModal = () => {
  showEditModal.value = false
}

const submitEdit = async () => {
  if (!editForm.value.aluno_nome || !editForm.value.turma || !editForm.value.horario_saida) {
    toast('Preencha todos os campos obrigatórios', 'error')
    return
  }
  
  loadingEdit.value = true
  try {
    await update(editForm.value.id, {
      aluno_nome: editForm.value.aluno_nome,
      turma: editForm.value.turma,
      turno: editForm.value.turno,
      motivo_saida: editForm.value.motivo_saida,
      horario_saida: editForm.value.horario_saida,
      aula_numero: editForm.value.aula_numero,
      tipo: editForm.value.tipo,
      com_falta: editForm.value.com_falta === 'true',
      status: editForm.value.status,
      observacoes: editForm.value.observacoes
    })
    toast('Autorização atualizada com sucesso!', 'success')
    closeEditModal()
    await loadData()
  } catch (error) {
    console.error('Erro ao editar:', error)
    toast('Erro ao atualizar autorização', 'error')
  } finally {
    loadingEdit.value = false
  }
}

const openDeleteModal = (auth) => {
  deleteAuth.value = auth
  showDeleteModal.value = true
}

const openDeleteModalFromDetails = () => {
  if (selectedAuth.value) {
    openDeleteModal(selectedAuth.value)
    closeDetailsModal()
  }
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  deleteAuth.value = null
}

const confirmDelete = async () => {
  if (!deleteAuth.value) return
  loadingDelete.value = true
  try {
    await cancel(deleteAuth.value.id)
    toast('Autorização excluída com sucesso!', 'success')
    closeDeleteModal()
    await loadData()
  } catch (error) {
    console.error('Erro ao excluir:', error)
    toast('Erro ao excluir autorização', 'error')
  } finally {
    loadingDelete.value = false
  }
}

const statusBadge = (status) => {
  const badges = {
    approved_by_professor: 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
    completed: 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
    cancelled: 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-400'
  }
  return badges[status] || 'bg-green-100 text-green-700'
}

const statusText = (status) => {
  const texts = {
    approved_by_professor: 'Autorizada',
    completed: 'Concluída',
    cancelled: 'Cancelada'
  }
  return texts[status] || status
}

const formatTime = (time) => time?.substring(0, 5) || '—'
const formatDateTime = (date) => date ? new Date(date).toLocaleString('pt-BR') : '—'
const formatDate = (date) => date ? new Date(date).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' }) : '—'

onMounted(() => loadData())
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>