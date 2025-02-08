<template>
    <div>
      <table class="table mt-4">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="certificate in certificates" :key="certificate.id">
            <td>{{ certificate.id }}</td>
            <td>{{ certificate.title }}</td>
            <td>
              <a :href="editRoute(certificate.id)" class="btn btn-warning">Edit</a>
              <button @click="deleteCertificate(certificate)" class="btn btn-danger">Delete</button>
              <a :href="showRoute(certificate.id)">View</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: {
      initialCertificates: Array,
      editRouteTemplate: String,
      showRouteTemplate: String,
      deleteRouteTemplate: String
    },
    data() {
      return {
        certificates: this.initialCertificates
      };
    },
    methods: {
      editRoute(id) {
        return this.editRouteTemplate.replace(':id', id);
      },
      showRoute(id) {
        return this.showRouteTemplate.replace(':id', id);
      },
      async deleteCertificate(certificate) {
        if (confirm(`Are you sure you want to delete "${certificate.title}"?`)) {
          try {
            await axios.delete(this.deleteRouteTemplate.replace(':id', certificate.id), {
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
            });
            this.certificates = this.certificates.filter(c => c.id !== certificate.id);
          } catch (error) {
            console.error('Error deleting certificate:', error);
            alert('Failed to delete certificate. Please try again.');
          }
        }
      }
    }
  };
  </script>