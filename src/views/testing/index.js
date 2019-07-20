var crudapp = new Vue({
    delimiters: ['v{', '}e'],
    el: "#crudapp",
    data: {
        name: "",
        email: "",
        city: "",
        country: "",
        job: "",
        contacts: []
    },
    mounted: function() {
        console.log("Hello from Vue")
        this.getContacts()
    },
    methods: {
        getContacts: function() {
            axios.get('api/contacts').then(function(response) {
                console.log(response.data)
                crudapp.contacts = response.data
            }).catch(function (error) {
                console.log(error)
            })
        },
        createContact: function() {
            console.log("Create contact.")

            let formData = new FormData();
            console.log("name: " + this.name)
            formData.append("name", this.name)
            formData.append("email", this.email)
            formData.append("city", this.city)
            formData.append("country", this.country)
            formData.append("job", this.job)

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
                console.log(response)
                crudapp.contacts.push(contact)
                crudapp.resetForm()
            }).catch(function(error) {
                // handle error
                console.log(error)
            })
        },
        resetForm: function() {
            this.name = ""
            this.email = ""
            this.city = ""
            this.country = ""
            this.job = ""
        },
        deleteContact: function(id) {
            console.log("Delete " + id)

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
        }
    }
})
