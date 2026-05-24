<template>
  <div class="space-y-8">
    <!-- KPI Skeletons -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      <div v-for="i in 4" :key="i" class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 animate-pulse">
        <div class="flex justify-between items-start">
          <div class="space-y-3 flex-1">
            <div class="h-3 bg-slate-100 dark:bg-slate-700 rounded w-2/3"></div>
            <div class="h-8 bg-slate-100 dark:bg-slate-700 rounded w-1/2"></div>
            <div class="h-3 bg-slate-100 dark:bg-slate-700 rounded w-1/4"></div>
          </div>
          <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-2xl"></div>
        </div>
      </div>
    </div>
    
    <!-- Charts Skeletons -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 p-6 animate-pulse">
        <div class="h-6 bg-slate-100 dark:bg-slate-700 rounded w-1/3 mb-2"></div>
        <div class="h-4 bg-slate-100 dark:bg-slate-700 rounded w-1/2 mb-6"></div>
        <div class="h-64 bg-slate-100 dark:bg-slate-700 rounded-2xl"></div>
      </div>
      <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 p-6 animate-pulse">
        <div class="h-6 bg-slate-100 dark:bg-slate-700 rounded w-1/3 mb-2"></div>
        <div class="h-4 bg-slate-100 dark:bg-slate-700 rounded w-1/2 mb-6"></div>
        <div class="h-64 bg-slate-100 dark:bg-slate-700 rounded-2xl"></div>
      </div>
    </div>
  </div>
</template>