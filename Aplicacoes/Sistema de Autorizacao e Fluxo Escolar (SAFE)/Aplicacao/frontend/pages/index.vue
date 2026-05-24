<script setup>
// Redireciona automaticamente quem cair na raiz para o login
definePageMeta({
  middleware: [
    function (to, from) {
      return navigateTo('/login')
    },
  ],
});
</script>