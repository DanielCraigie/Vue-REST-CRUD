Vue.component('modal', {
    template: '#modal-template'
})

var crudapp = new Vue({
    delimiters: ['v{', '}e'],
    el: "#crudapp",
    data: {
        name: { value: "", error: false },
        email: { value: "", error: false },
        city: { value: "", error: false },
        country: { value: "", error: false },
        job: { value: "", error: false },
        contacts: [],
        errors: [],
        showModal: false
    },
    mounted: function() {
        this.getContacts()
    },
    methods: {
        getContacts: function() {
            axios.get('api/contacts').then(function(response) {
                crudapp.contacts = response.data
            }).catch(function (error) {
                console.log(error)
            })
        },
        createContact: function() {
            let formData = new FormData();

            formData.append("name", this.name.value)
            formData.append("email", this.email.value)
            formData.append("city", this.city.value)
            formData.append("country", this.country.value)
            formData.append("job", this.job.value)

            var contact = {};
            formData.forEach(function(value, key) {
                contact[key] = value
            })

            axios({
                method: "post",
                url: "api/contacts",
                data: formData,
                config: { headers: { "Content-Type": "multipart/form-data" } }
            }).then(function(response) {
                // handle success
                contact["id"] = response.data.id
                crudapp.contacts.push(contact)
                crudapp.resetForm()
            }).catch(function(error) {
                // handle error
                console.log(error)
            })
        },
        resetForm: function() {
            this.name.value = ""
            this.email.value = ""
            this.city.value = ""
            this.country.value = ""
            this.job.value = ""

            this.showModal = false
        },
        deleteContact: function(id) {
            axios({
                method: "delete",
                url: "api/contacts/" + id,
            }).then(function(response) {
                // handle success
                crudapp.contacts.splice(crudapp.contacts.findIndex(obj => obj.id == id), 1)
                crudapp.resetForm()
            }).catch(function(error) {
                // handle error
                console.log(error)
            })
        },
        checkForm: function(e) {
            // validate fields
            if (this.name.value.length < 1) {
                this.errors.push("You must provide the Contacts Name")
                this.name.error = true
            }

            if (this.errors.length == 0) {
                this.createContact()
            }
        }
    }
})
