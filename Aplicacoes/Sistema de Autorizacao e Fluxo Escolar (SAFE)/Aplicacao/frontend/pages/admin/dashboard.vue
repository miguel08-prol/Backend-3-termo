<template>
  <div class="space-y-8 animate-fadeIn">
    <!-- Hero Section melhorada -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-red-500 via-red-600 to-rose-600 p-8 text-white shadow-2xl">
      <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
      <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
      
      <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center gap-3 mb-3">
            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
              <Icon name="heroicons:chart-pie-solid" class="w-6 h-6" />
            </div>
            <div>
              <h1 class="text-3xl font-bold">Dashboard SAFE</h1>
              <p class="text-red-100">Sistema de Autorização de Saída Escolar</p>
            </div>
          </div>
          <div class="flex flex-wrap gap-4 mt-4 text-sm">
            <div class="flex items-center gap-2">
              <Icon name="heroicons:calendar-days-20-solid" class="w-4 h-4" />
              <span>{{ currentDate }}</span>
            </div>
            <div class="flex items-center gap-2">
              <Icon name="heroicons:clock-20-solid" class="w-4 h-4" />
              <span>{{ currentTime }}</span>
            </div>
            <div class="flex items-center gap-2">
              <Icon name="heroicons:users-20-solid" class="w-4 h-4" />
              <span>{{ stats.total || 0 }} autorizações totais</span>
            </div>
          </div>
        </div>
        
        <!-- Seletor de Período melhorado -->
        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-2xl p-1">
          <button 
            v-for="period in periods" 
            :key="period.value"
            @click="selectedPeriod = period.value; loadDashboardData()"
            :class="[
              'px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-300',
              selectedPeriod === period.value 
                ? 'bg-white text-red-600 shadow-lg' 
                : 'text-white/80 hover:bg-white/10'
            ]"
          >
            <Icon :name="period.icon" class="w-4 h-4 inline mr-1" />
            {{ period.label }}
          </button>
        </div>
      </div>
    </div>

    <!-- Stats Cards com animações -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <div 
        v-for="(stat, index) in statCards" 
        :key="stat.key"
        class="group relative bg-white dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 cursor-pointer"
        :style="{ animationDelay: `${index * 0.05}s` }"
        @click="filterByStatus(stat.filterKey)"
      >
        <!-- Indicador de tendência -->
        <div class="absolute top-4 right-4">
          <div class="flex items-center gap-1 text-xs" :class="stat.trend > 0 ? 'text-green-500' : 'text-red-500'">
            <Icon :name="stat.trend > 0 ? 'heroicons:arrow-trending-up-20-solid' : 'heroicons:arrow-trending-down-20-solid'" class="w-3 h-3" />
            <span>{{ Math.abs(stat.trend) }}%</span>
          </div>
        </div>
        
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">{{ stat.label }}</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ formatNumber(stats[stat.key]) }}</p>
          </div>
          <div :class="[
            'w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-6',
            stat.bgColor
          ]">
            <Icon :name="stat.icon" class="w-6 h-6" :class="stat.iconColor" />
          </div>
        </div>
        
        <!-- Barra de progresso para taxa de aprovação -->
        <div v-if="stat.key === 'taxa_aprovacao'" class="mt-4">
          <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-green-400 to-green-500 rounded-full transition-all duration-500" :style="{ width: `${stats.taxa_aprovacao || 0}%` }"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gráficos principais -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Gráfico de Rosquinha interativo -->
      <AppCard class="overflow-hidden">
        <template #header>
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-bold text-slate-800 dark:text-white">Status das Autorizações</h2>
              <p class="text-xs text-slate-400">Distribuição atual</p>
            </div>
            <div class="flex items-center gap-2">
              <button @click="loadDashboardData" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-all duration-300" :class="{ 'animate-spin': loading }">
                <Icon name="heroicons:arrow-path-20-solid" class="w-4 h-4 text-slate-400" />
              </button>
            </div>
          </div>
        </template>
        
        <div class="flex flex-col lg:flex-row items-center gap-6 py-4">
          <div class="relative w-48 h-48">
            <canvas ref="donutChartCanvas"></canvas>
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="text-center bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm rounded-full w-20 h-20 flex flex-col items-center justify-center shadow-lg">
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.total || 0 }}</p>
                <p class="text-[10px] text-slate-500">Total</p>
              </div>
            </div>
          </div>
          
          <div class="flex-1 grid grid-cols-2 gap-2">
            <div 
              v-for="status in statusList.filter(s => s.showInDonut)" 
              :key="status.key"
              class="flex items-center justify-between p-3 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all duration-200 group"
              @click="filterByStatus(status.filterKey)"
            >
              <div class="flex items-center gap-2">
                <div :class="['w-3 h-3 rounded-full', status.color]"></div>
                <span class="text-sm text-slate-600 dark:text-slate-400">{{ status.label }}</span>
              </div>
              <span class="text-lg font-bold text-slate-900 dark:text-white">{{ stats[status.key] || 0 }}</span>
            </div>
          </div>
        </div>
      </AppCard>

      <!-- Gráfico de Barras melhorado -->
      <AppCard class="overflow-hidden">
        <template #header>
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-bold text-slate-800 dark:text-white">Movimento Recente</h2>
              <p class="text-xs text-slate-400">Últimos 7 dias</p>
            </div>
            <div class="flex gap-1">
              <button @click="chartType = 'bar'" class="p-2 rounded-lg" :class="chartType === 'bar' ? 'bg-red-50 text-red-500' : 'text-slate-400'">
                <Icon name="heroicons:chart-bar-20-solid" class="w-4 h-4" />
              </button>
              <button @click="chartType = 'line'" class="p-2 rounded-lg" :class="chartType === 'line' ? 'bg-red-50 text-red-500' : 'text-slate-400'">
                <Icon name="heroicons:chart-line-20-solid" class="w-4 h-4" />
              </button>
            </div>
          </div>
        </template>
        <div class="h-64">
          <canvas ref="barChartCanvas"></canvas>
        </div>
      </AppCard>
    </div>

    <!-- Segundo row de gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Distribuição por horário -->
      <AppCard>
        <template #header>
          <h2 class="text-lg font-bold text-slate-800 dark:text-white">Saídas por Horário</h2>
        </template>
        <div class="h-48">
          <canvas ref="hourlyChartCanvas"></canvas>
        </div>
      </AppCard>

      <!-- Top Professores -->
      <AppCard>
        <template #header>
          <h2 class="text-lg font-bold text-slate-800 dark:text-white">Top Professores</h2>
        </template>
        <div v-if="topProfessors.length === 0" class="text-center py-8 text-slate-400">
          <Icon name="heroicons:academic-cap-20-solid" class="w-12 h-12 mx-auto mb-2 opacity-50" />
          <p>Sem dados suficientes</p>
        </div>
        <div v-else class="space-y-3">
          <div v-for="(prof, idx) in topProfessors" :key="prof.name" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-100 to-red-200 dark:from-red-500/20 dark:to-red-600/20 flex items-center justify-center font-bold text-red-600">
              {{ idx + 1 }}
            </div>
            <div class="flex-1">
              <div class="flex justify-between text-sm mb-1">
                <span class="font-medium text-slate-700 dark:text-slate-300">{{ prof.name }}</span>
                <span class="text-red-500 font-bold">{{ prof.total }}</span>
              </div>
              <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-red-400 to-red-500 rounded-full" :style="{ width: `${(prof.total / topProfessors[0].total) * 100}%` }"></div>
              </div>
            </div>
          </div>
        </div>
      </AppCard>
    </div>

    <!-- Últimas Autorizações -->
    <AppCard class="overflow-hidden">
      <template #header>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Últimas Autorizações</h2>
            <p class="text-xs text-slate-400 flex items-center gap-2">
              <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
              Atualizado em tempo real
            </p>
          </div>
          <div class="flex gap-3">
            <button 
              @click="exportData" 
              class="px-4 py-2 text-sm bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 flex items-center gap-2 shadow-lg shadow-emerald-500/20"
            >
              <Icon name="heroicons:document-arrow-down-20-solid" class="w-4 h-4" />
              Exportar
            </button>
            <NuxtLink to="/admin/authorizations" class="px-4 py-2 text-sm bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl transition-all duration-300 flex items-center gap-2 shadow-lg shadow-red-500/20">
              Ver todas
              <Icon name="heroicons:arrow-right-20-solid" class="w-4 h-4" />
            </NuxtLink>
          </div>
        </div>
      </template>

      <div v-if="loadingLast" class="text-center py-16">
        <div class="inline-flex flex-col items-center gap-3">
          <Icon name="heroicons:arrow-path-20-solid" class="w-10 h-10 animate-spin text-red-500" />
          <p class="text-slate-500">Carregando autorizações...</p>
        </div>
      </div>

      <div v-else-if="lastAuthorizations.length === 0" class="text-center py-16">
        <div class="inline-flex flex-col items-center gap-3">
          <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
            <Icon name="heroicons:document-text-20-solid" class="w-10 h-10 text-slate-300 dark:text-slate-600" />
          </div>
          <p class="text-slate-500 dark:text-slate-400">Nenhuma autorização encontrada</p>
        </div>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 border-b-2 border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Aluno</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Turma</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Horário</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Professor</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Data</th>
              <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="auth in lastAuthorizations" :key="auth.id" class="hover:bg-gradient-to-r hover:from-slate-50 hover:to-transparent dark:hover:from-slate-800/30 transition-all duration-200 group">
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
                <div class="text-sm text-slate-500">{{ formatDateTime(auth.created_at) }}</div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-1">
                  <button @click="viewDetails(auth)" class="p-2 rounded-lg text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all duration-200" title="Ver detalhes">
                    <Icon name="heroicons:eye-20-solid" class="w-5 h-5" />
                  </button>
                  <button @click="quickDelete(auth)" v-if="auth.status === 'pending'" class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all duration-200" title="Excluir">
                    <Icon name="heroicons:trash-20-solid" class="w-5 h-5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </AppCard>

    <!-- Modal de Detalhes melhorado (sem X, com animação) -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" @click.self="closeModal">
          <div class="relative max-w-2xl w-full bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden animate-slideUp">
            <!-- Header sem X -->
            <div class="relative bg-gradient-to-r from-red-500 to-red-600 p-6 text-white">
              <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                  <Icon name="heroicons:document-text-20-solid" class="w-8 h-8" />
                </div>
                <div>
                  <h3 class="text-2xl font-bold">Detalhes da Autorização</h3>
                  <p class="text-red-100 text-sm">ID: #{{ selectedAuth?.id }}</p>
                </div>
              </div>
            </div>

            <div class="p-6 max-h-[70vh] overflow-y-auto">
              <div v-if="selectedAuth" class="space-y-6">
                <!-- Informações do Aluno -->
                <div class="grid grid-cols-2 gap-4">
                  <div class="p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 rounded-xl">
                    <p class="text-xs text-slate-500 mb-1 flex items-center gap-1">
                      <Icon name="heroicons:user-20-solid" class="w-3 h-3" />
                      Aluno
                    </p>
                    <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ selectedAuth.aluno_nome }}</p>
                  </div>
                  
                  <div class="p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 rounded-xl">
                    <p class="text-xs text-slate-500 mb-1 flex items-center gap-1">
                      <Icon name="heroicons:users-20-solid" class="w-3 h-3" />
                      Turma
                    </p>
                    <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ selectedAuth.turma }}</p>
                  </div>
                </div>

                <!-- Horário e Aula -->
                <div class="grid grid-cols-2 gap-4">
                  <div class="p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 rounded-xl">
                    <p class="text-xs text-slate-500 mb-1 flex items-center gap-1">
                      <Icon name="heroicons:clock-20-solid" class="w-3 h-3" />
                      Horário de Saída
                    </p>
                    <p class="font-semibold text-slate-900 dark:text-white text-2xl font-mono">{{ formatTime(selectedAuth.horario_saida) }}</p>
                    <p class="text-xs text-slate-400">{{ selectedAuth.aula_numero }}ª Aula</p>
                  </div>
                  
                  <div class="p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800/30 rounded-xl">
                    <p class="text-xs text-slate-500 mb-1 flex items-center gap-1">
                      <Icon name="heroicons:academic-cap-20-solid" class="w-3 h-3" />
                      Status
                    </p>
                    <span :class="statusBadge(selectedAuth.status)" class="px-3 py-1.5 text-sm font-semibold rounded-full inline-flex items-center gap-1">
                      <span :class="statusDot(selectedAuth.status)"></span>
                      {{ statusText(selectedAuth.status) }}
                    </span>
                  </div>
                </div>

                <!-- Professor e Criador -->
                <div class="grid grid-cols-2 gap-4">
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
                    <p class="text-xs text-slate-400">{{ formatDateTime(selectedAuth.created_at) }}</p>
                  </div>
                </div>

                <!-- Motivo da Saída -->
                <div v-if="selectedAuth.motivo_saida" class="p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-500/10 dark:to-yellow-600/5 rounded-xl border border-yellow-200 dark:border-yellow-500/20">
                  <p class="text-xs text-yellow-700 dark:text-yellow-400 mb-1 flex items-center gap-1">
                    <Icon name="heroicons:information-circle-20-solid" class="w-3 h-3" />
                    Motivo da Saída
                  </p>
                  <p class="text-slate-700 dark:text-slate-300">{{ selectedAuth.motivo_saida }}</p>
                </div>

                <!-- Observações -->
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
                    <span class="text-xs text-slate-400">{{ formatDateTime(selectedAuth.validation.validated_at) }}</span>
                  </div>
                  <p v-if="selectedAuth.validation.com_falta" class="text-sm text-amber-600 dark:text-amber-400 flex items-center gap-1">
                    <Icon name="heroicons:exclamation-triangle-20-solid" class="w-4 h-4" />
                    Aluno receberá falta
                  </p>
                  <p v-if="selectedAuth.validation.observacao" class="text-sm text-slate-600 dark:text-slate-400 mt-2">{{ selectedAuth.validation.observacao }}</p>
                </div>
              </div>
            </div>

            <!-- Footer com botões -->
            <div class="border-t border-slate-200 dark:border-slate-700 p-6 bg-slate-50 dark:bg-slate-800/30">
              <div class="flex justify-end gap-3">
                <button @click="closeModal" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium">
                  Fechar
                </button>
                <button v-if="selectedAuth?.status === 'pending'" @click="quickDelete(selectedAuth); closeModal()" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white transition-colors flex items-center gap-2 font-medium">
                  <Icon name="heroicons:trash-20-solid" class="w-4 h-4" />
                  Excluir Autorização
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Modal de Confirmação de Exclusão melhorado -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" @click.self="showDeleteModal = false">
          <div class="relative max-w-md w-full bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden animate-slideUp">
            <div class="text-center p-8">
              <div class="w-20 h-20 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center mx-auto mb-4">
                <Icon name="heroicons:exclamation-triangle-20-solid" class="w-10 h-10 text-red-500" />
              </div>
              <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Confirmar Exclusão</h3>
              <p class="text-slate-500 dark:text-slate-400 mb-6">
                Tem certeza que deseja excluir a autorização de <strong class="text-slate-900 dark:text-white">{{ deleteAuth?.aluno_nome }}</strong>?<br>
                Esta ação não pode ser desfeita.
              </p>
              <div class="flex gap-3">
                <button @click="showDeleteModal = false" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium">
                  Cancelar
                </button>
                <button @click="confirmDelete" :disabled="loadingDelete" class="flex-1 px-4 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white transition-colors flex items-center justify-center gap-2 font-medium">
                  <Icon v-if="loadingDelete" name="heroicons:arrow-path-20-solid" class="w-4 h-4 animate-spin" />
                  <Icon v-else name="heroicons:trash-20-solid" class="w-4 h-4" />
                  {{ loadingDelete ? 'Excluindo...' : 'Confirmar' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useApi } from '~/composables/useApi'
import { useToast } from '~/composables/useToast'
import { useAuthStore } from '~/stores/auth'
import AppCard from '~/components/common/AppCard.vue'
import Chart from 'chart.js/auto'

definePageMeta({ layout: 'admin' })

const { get, del } = useApi()
const { show: toast } = useToast()
const authStore = useAuthStore()

// State
const loading = ref(false)
const loadingLast = ref(false)
const loadingDelete = ref(false)
const stats = ref({
  total: 0,
  pending: 0,
  approved: 0,
  completed: 0,
  rejected: 0,
  cancelled: 0,
  com_falta: 0,
  taxa_aprovacao: 0,
  tempo_medio_resposta: null
})
const lastAuthorizations = ref([])
const dailyStats = ref([])
const hourlyStats = ref([])
const topProfessors = ref([])
const selectedPeriod = ref('week')
const selectedAuth = ref(null)
const showDetailsModal = ref(false)
const showDeleteModal = ref(false)
const deleteAuth = ref(null)
const chartType = ref('bar')

// Chart refs
const donutChartCanvas = ref(null)
const barChartCanvas = ref(null)
const hourlyChartCanvas = ref(null)
let donutChart = null
let barChart = null
let hourlyChart = null

// Current date/time
const currentDate = computed(() => new Date().toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' }))
const currentTime = ref(new Date().toLocaleTimeString('pt-BR'))

// Update time every second
let timeInterval
onMounted(() => {
  timeInterval = setInterval(() => {
    currentTime.value = new Date().toLocaleTimeString('pt-BR')
  }, 1000)
})
onUnmounted(() => {
  clearInterval(timeInterval)
  if (donutChart) donutChart.destroy()
  if (barChart) barChart.destroy()
  if (hourlyChart) hourlyChart.destroy()
})

const periods = [
  { value: 'week', label: 'Semana', icon: 'heroicons:calendar' },
  { value: 'month', label: 'Mês', icon: 'heroicons:calendar-days' },
  { value: 'year', label: 'Ano', icon: 'heroicons:calendar' }
]

const statCards = [
  { key: 'total', label: 'Total de Autorizações', icon: 'heroicons:document-text', bgColor: 'bg-red-100 dark:bg-red-500/20', iconColor: 'text-red-600 dark:text-red-400', filterKey: null, trend: 12 },
  { key: 'pending', label: 'Aguardando Professor', icon: 'heroicons:clock', bgColor: 'bg-yellow-100 dark:bg-yellow-500/20', iconColor: 'text-yellow-600 dark:text-yellow-400', filterKey: 'pending', trend: -5 },
  { key: 'approved', label: 'Aprovadas', icon: 'heroicons:check-badge', bgColor: 'bg-green-100 dark:bg-green-500/20', iconColor: 'text-green-600 dark:text-green-400', filterKey: 'approved_by_professor', trend: 8 },
  { key: 'taxa_aprovacao', label: 'Taxa de Aprovação', icon: 'heroicons:chart-bar', bgColor: 'bg-emerald-100 dark:bg-emerald-500/20', iconColor: 'text-emerald-600 dark:text-emerald-400', filterKey: null, trend: 3 }
]

const statusList = [
  { key: 'pending', label: 'Pendentes', filterKey: 'pending', color: 'bg-yellow-500', showInDonut: true },
  { key: 'approved', label: 'Aprovadas', filterKey: 'approved_by_professor', color: 'bg-green-500', showInDonut: true },
  { key: 'completed', label: 'Concluídas', filterKey: 'completed', color: 'bg-blue-500', showInDonut: true },
  { key: 'rejected', label: 'Rejeitadas', filterKey: 'rejected', color: 'bg-red-500', showInDonut: true },
  { key: 'cancelled', label: 'Canceladas', filterKey: 'cancelled', color: 'bg-gray-500', showInDonut: false }
]

const loadDashboardData = async () => {
  loading.value = true
  try {
    console.log('Carregando dados do dashboard...')
    console.log('Usuário logado:', authStore.user?.role)
    
    const response = await get('/admin/dashboard/stats')
    console.log('Resposta recebida:', response)
    
    stats.value = response.stats
    dailyStats.value = response.daily || []
    hourlyStats.value = response.hourly || []
    topProfessors.value = response.top_professors || []
    
    loadingLast.value = true
    const authResponse = await get('/authorizations', { per_page: 10 })
    lastAuthorizations.value = authResponse.data || authResponse
    
    // Aguardar um tick para garantir que os canvas estão renderizados
    setTimeout(() => {
      renderCharts()
    }, 100)
  } catch (error) {
    console.error('Erro ao carregar dashboard:', error)
    toast(error.message || 'Erro ao carregar dados do dashboard', 'error')
  } finally {
    loading.value = false
    loadingLast.value = false
  }
}

const renderCharts = () => {
  // Donut chart
  if (donutChartCanvas.value) {
    try {
      if (donutChart) donutChart.destroy()
      const ctx = donutChartCanvas.value.getContext('2d')
      donutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Pendentes', 'Aprovadas', 'Concluídas', 'Rejeitadas'],
          datasets: [{
            data: [stats.value.pending, stats.value.approved, stats.value.completed, stats.value.rejected],
            backgroundColor: ['#eab308', '#22c55e', '#3b82f6', '#ef4444'],
            borderWidth: 0,
            cutout: '65%',
            hoverOffset: 15
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: { 
            legend: { display: false }, 
            tooltip: { 
              callbacks: { 
                label: (ctx) => `${ctx.label}: ${ctx.raw} (${((ctx.raw / stats.value.total) * 100).toFixed(1)}%)` 
              } 
            } 
          }
        }
      })
    } catch (error) {
      console.error('Erro ao renderizar donut chart:', error)
    }
  }

  // Bar/Line chart
  if (barChartCanvas.value && dailyStats.value.length > 0) {
    try {
      if (barChart) barChart.destroy()
      const ctx = barChartCanvas.value.getContext('2d')
      const isBar = chartType.value === 'bar'
      barChart = new Chart(ctx, {
        type: isBar ? 'bar' : 'line',
        data: {
          labels: dailyStats.value.slice(-7).map(d => d.day),
          datasets: [
            { 
              label: 'Criadas', 
              data: dailyStats.value.slice(-7).map(d => d.total), 
              backgroundColor: '#ef4444', 
              borderColor: '#ef4444', 
              borderRadius: 8, 
              borderWidth: isBar ? 0 : 2, 
              fill: false, 
              tension: 0.3,
              pointBackgroundColor: '#ef4444',
              pointBorderColor: '#ef4444'
            },
            { 
              label: 'Aprovadas', 
              data: dailyStats.value.slice(-7).map(d => d.approved || 0), 
              backgroundColor: '#22c55e', 
              borderColor: '#22c55e', 
              borderRadius: 8, 
              borderWidth: isBar ? 0 : 2, 
              fill: false, 
              tension: 0.3,
              pointBackgroundColor: '#22c55e',
              pointBorderColor: '#22c55e'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: { 
            legend: { position: 'top' }, 
            tooltip: { mode: 'index', intersect: false } 
          },
          scales: { 
            y: { 
              beginAtZero: true, 
              ticks: { stepSize: 1, precision: 0 },
              grid: { color: '#e2e8f0' }
            },
            x: { grid: { display: false } }
          }
        }
      })
    } catch (error) {
      console.error('Erro ao renderizar bar/line chart:', error)
    }
  }

  // Hourly chart
  if (hourlyChartCanvas.value && hourlyStats.value.length > 0) {
    try {
      if (hourlyChart) hourlyChart.destroy()
      const ctx = hourlyChartCanvas.value.getContext('2d')
      hourlyChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: hourlyStats.value.map(h => h.label),
          datasets: [{
            label: 'Saídas', 
            data: hourlyStats.value.map(h => h.count), 
            backgroundColor: 'rgba(239, 68, 68, 0.2)', 
            borderColor: '#ef4444', 
            borderWidth: 2, 
            fill: true, 
            tension: 0.4, 
            pointBackgroundColor: '#ef4444', 
            pointRadius: 4, 
            pointHoverRadius: 6
          }]
        },
        options: { 
          responsive: true, 
          maintainAspectRatio: true, 
          plugins: { legend: { display: false } }, 
          scales: { 
            y: { 
              beginAtZero: true, 
              ticks: { stepSize: 1 },
              grid: { color: '#e2e8f0' }
            },
            x: { grid: { display: false } }
          } 
        }
      })
    } catch (error) {
      console.error('Erro ao renderizar hourly chart:', error)
    }
  }
}

// Watch chart type
watch(chartType, () => {
  setTimeout(() => renderCharts(), 50)
})

const filterByStatus = (status) => {
  if (status) navigateTo(`/admin/authorizations?status=${status}`)
}

const viewDetails = (auth) => {
  selectedAuth.value = auth
  showDetailsModal.value = true
}

const closeModal = () => {
  showDetailsModal.value = false
  selectedAuth.value = null
}

const quickDelete = (auth) => {
  deleteAuth.value = auth
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteAuth.value) return
  loadingDelete.value = true
  try {
    await del(`/authorizations/${deleteAuth.value.id}`)
    toast('Autorização excluída com sucesso!', 'success')
    showDeleteModal.value = false
    deleteAuth.value = null
    await loadDashboardData()
  } catch (error) {
    console.error('Erro ao excluir autorização:', error)
    toast(error.message || 'Erro ao excluir autorização', 'error')
  } finally {
    loadingDelete.value = false
  }
}

const exportData = () => {
  if (lastAuthorizations.value.length === 0) {
    toast('Não há dados para exportar', 'warning')
    return
  }
  
  const data = lastAuthorizations.value.map(auth => ({
    'ID': auth.id, 
    'Aluno': auth.aluno_nome, 
    'Turma': auth.turma, 
    'Horário': auth.horario_saida,
    'Aula': `${auth.aula_numero}ª`, 
    'Professor': auth.professor?.name || '-', 
    'Status': statusText(auth.status),
    'Motivo': auth.motivo_saida || '', 
    'Data Criação': formatDateTime(auth.created_at),
    'Data Saída': auth.gatewayEntry?.horario_saida ? formatDateTime(auth.gatewayEntry.horario_saida) : '-'
  }))
  
  const headers = Object.keys(data[0])
  const csvRows = []
  csvRows.push(headers.join(','))
  
  for (const row of data) {
    const values = headers.map(header => {
      const value = row[header] || ''
      return `"${String(value).replace(/"/g, '""')}"`
    })
    csvRows.push(values.join(','))
  }
  
  const blob = new Blob(['\uFEFF' + csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `autorizacoes_${new Date().toISOString().split('T')[0]}.csv`
  a.click()
  URL.revokeObjectURL(url)
  toast('Exportação realizada com sucesso!', 'success')
}

const statusBadge = (status) => {
  const badges = {
    pending: 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-900 shadow-md',
    approved_by_professor: 'bg-gradient-to-r from-green-400 to-green-500 text-green-900 shadow-md',
    rejected: 'bg-gradient-to-r from-red-400 to-red-500 text-red-900 shadow-md',
    completed: 'bg-gradient-to-r from-blue-400 to-blue-500 text-blue-900 shadow-md',
    cancelled: 'bg-gradient-to-r from-gray-400 to-gray-500 text-gray-900 shadow-md'
  }
  return badges[status] || 'bg-gray-100 text-gray-700'
}

const statusDot = (status) => {
  const dots = { 
    pending: 'bg-yellow-900', 
    approved_by_professor: 'bg-green-900', 
    rejected: 'bg-red-900', 
    completed: 'bg-blue-900', 
    cancelled: 'bg-gray-900' 
  }
  return `w-1.5 h-1.5 rounded-full ${dots[status] || 'bg-gray-900'}`
}

const statusText = (status) => {
  const texts = { 
    pending: 'Pendente', 
    approved_by_professor: 'Aprovado', 
    rejected: 'Rejeitado', 
    completed: 'Concluído', 
    cancelled: 'Cancelado' 
  }
  return texts[status] || status
}

const formatNumber = (num) => {
  if (num === undefined || num === null) return '0'
  return num.toLocaleString()
}

const formatTime = (time) => {
  if (!time) return '—'
  return time.substring(0, 5)
}

const formatDateTime = (date) => {
  if (!date) return '—'
  try {
    return new Date(date).toLocaleString('pt-BR')
  } catch {
    return date
  }
}

// Refresh data periodically (every 30 seconds)
let refreshInterval
const startAutoRefresh = () => {
  refreshInterval = setInterval(() => {
    if (!showDetailsModal.value && !showDeleteModal.value) {
      loadDashboardData()
    }
  }, 30000)
}

const stopAutoRefresh = () => {
  if (refreshInterval) clearInterval(refreshInterval)
}

onMounted(() => {
  loadDashboardData()
  startAutoRefresh()
})

onUnmounted(() => {
  stopAutoRefresh()
  if (timeInterval) clearInterval(timeInterval)
  if (donutChart) donutChart.destroy()
  if (barChart) barChart.destroy()
  if (hourlyChart) hourlyChart.destroy()
})
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn { animation: fadeIn 0.5s ease-out; }
.animate-slideUp { animation: slideUp 0.3s ease-out; }

.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
</style>