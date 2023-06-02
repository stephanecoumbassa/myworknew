<template>
  <q-select
    v-model="selectedTaskId"
    :options="tasks"
    label="Tâches"
    map-options
    emit-value
    multiple
    option-value="id"
    option-label="name"
  />
</template>

<script>
export default {
  name: 'TaskSelect',
  data () {
    return {
      selectedTaskId: null,
      tasks: [
        { id: 1, name: 'libelle' },
        { id: 2, name: 'description' },
        { id: 3, name: 'status' },
        { id: 4, name: 'p_projet_id' },
        { id: 5, name: 'debut' },
        { id: 6, name: 'fin' },
        { id: 7, name: 'tuuid' }
      ]
    }
  },
  // props: ['tasks'],
  computed: {
    selectedTask () {
      return this.tasks.find(task => task.id === this.selectedTaskId)
    }
  },
  watch: {
    selectedTask (newValue) {
      this.$emit('selected', newValue)
    }
  }
}
</script>
