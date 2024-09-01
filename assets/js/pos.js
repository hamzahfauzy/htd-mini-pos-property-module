Vue.createApp({
    data() {
        return {
            message: 'Memuat data...',
            data: [],
            properties: [],
            customers: [],
            sales: [],
            form:{
                customer: "",
                sales: "",
                transaction_type: "",
                issue_date: "",
                instalment: "",
                amount_of_instalment: "",
                due_date: "",
                paytotal: 0
            },
            selectedProperty: null,
            selectedItem: null,
            transactions: [],
            keyword:'',
            customer:'',
            notes:'',
            kembalian:0,
            itemCount:0,
            ringkasan_panel:false,
            bayar:0,
            pos_sess_id:window.pos_sess_id,
            transaction_id:window.transaction_id,
            isLoad:false
        }
    },
    async created(){
        await this.initPropertyData();
        window.openSidebar = this.openSidebar
        document.querySelector('#app').style.display = 'block'
        this.getCustomers()
        this.getSales()
    },
    methods:{
        testAlert(){
            alert(1)
        },
        numberFormat(number)
        {
            var formatter = new Intl.NumberFormat('en-US', {});

            return formatter.format(number)
        },
        async initPropertyData(){
            this.isLoad = false
            var req = await fetch('index.php?r=properties/get-all')
            var res = await req.json()
            this.properties = res.data
            if(this.properties.length)
                this.isLoad = true
            else
                this.message = '<i>Tidak ada data!</i>'
        },
        async setSelectedProperty(ev){
            const propertyId = ev.target.value
            this.selectedProperty = this.properties.find(property => property.id == propertyId)
            var req = await fetch(this.selectedProperty.map_image)
            this.selectedProperty.svg = await req.text()

            req = await fetch('index.php?r=property-items/get-all&property_id='+propertyId)
            var response = await req.json()
            response.data.forEach(item => {
                document.querySelector(`#${item.code}`).classList.add('cursor-pointer')
                document.querySelector(`#${item.code}`).classList.add(item.record_status)
                document.querySelector(`#${item.code}`).onclick = function(){
                    window.openSidebar(item)
                }
            })
        },
        async getCustomers(){
            const req = await fetch('index.php?r=api/customers/get-all')
            const response = await req.json()
            this.customers = response.data
        },
        async getSales(){
            const req = await fetch('index.php?r=api/users/get-all')
            const response = await req.json()
            this.sales = response.data
        },
        openSidebar(selectedItem){
            this.selectedItem = selectedItem
            this.ringkasan_panel = true
        },
        async doSubmit()
        {        
            var formData = new FormData
            formData.append('item', this.selectedItem.id)
            for(field in this.form)
            {
                formData.append(field, this.form[field])
            }
            
            var request = await fetch('index.php?r=property-items/order',{
                'method':'POST',
                'body':formData
            }).catch(function(error) {
                console.log(error)
            })

            var response = await request.json()
            if(request.ok && request.status == 200)
            {
                if(response.status == 'success') 
                {
                    var invoice = response.data;
                    if(typeof(Android) === "undefined") 
                    {
                        alert('Order Berhasil! Klik Oke untuk mencetak struk')
    
                        await fetch('index.php?r=print/invoice&inv_code='+invoice.code)
                        window.location = 'index.php?r=invoices/view&id='+invoice.id
                        return
                    }
                    else
                    {
                        cetakAndroid(invoice)
                    }
    
                    setTimeout(function(){
                        window.location = 'index.php?r=invoices/view&id='+invoice.id
                    },3000)
                    
                }
            }
            else
            {
                alert(response.message)
            }

        }
    }
}).mount('#app')