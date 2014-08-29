/**
 * Created by timofey on 28.07.14.
 */

(function(){
    window.App = {
        Models:{},
        Views:{},
        Collections:{},
        Attributes:{},
        Helpers:{}
    }



    App.Helpers.getTemplate = function(val)
    {
        return _.template($('#'+val+'Template').html());
    }

    App.Attributes.ExistProps = [];

//
    App.Models.Property = Backbone.Model.extend({
        defaults : {
            storage_events_id : 0,
            properties_id: 0,
            button_name:"Сохранить",
            properties:{},
            num:0
        },
        validate : function (attrs) {
            if(attrs.count < 2)
            {

            }
            else
            {
                if(parseInt(attrs.properties_id) == 0)
                {
                    return 'Ошибка при создании свойства!';
                }
            }

            console.log(attrs);
        },
        urlRoot:'/admin/proptoevent'
    });

    App.Views.Property = Backbone.View.extend({
        tagName : "li",
        template : App.Helpers.getTemplate("properties"),
        initialize :function(){
            this.model.on('change', this.render, this);
            this.model.on('invalid', this.errorAction, this);
            this.model.on('destroy', this.remove, this);
        },
        render :function(){

            var model = this.model.toJSON();
            model.properties = App.Attributes.PropertyList;
            var html = this.template(model);
            this.$el.html(html);
            return this;
        },
        save : function()
        {


        },
        errorAction : function(model, msg) {
            alert(msg);
        },
        remove : function()
        {
            this.$el.remove();
        },
        events : {
            'click .delete' : 'deleteTask',
            'click .edit' : 'editTask'
        },
        editTask : function()
        {
            var properties_id = parseInt(this.$el.find('.property-selector').val());


            if(App.Attributes.ExistProps.indexOf(properties_id) < 0)
            {
                var stringButtonName = this.$el.find('button.btn.btn-success.edit').text();
                if(stringButtonName == "Сохранить")
                {
                    stringButtonName =  "Обновить";
                    this.model.set({"button_name": "Обновить", "properties_id": properties_id},{validate:true});
                }
                else
                    this.model.set("properties_id", properties_id,{validate:true});
                App.Attributes.ExistProps.push(properties_id);
                this.model.save();
            }
            else
            {
                alert('Cвойство уже существует!');
            }


        },
        deleteTask : function()
        {
            var index = App.Attributes.ExistProps.indexOf(this.model.get('properties_id'));
            if(index >= 0){
                App.Attributes.ExistProps.splice(index, 1);
            }
            this.model.destroy();
        }
    });
//
//
        App.Collections.Properties = Backbone.Collection.extend({
            model:App.Models.Property
        });


//
    App.Views.Properties = Backbone.View.extend({
        tagName : "ul",
        render : function(){
            this.collection.each(this.addOne, this);
            return this;
        },
        initialize : function() {
            this.collection.on('add', this.addOne, this);
        },
        addOne : function(property){
            //создават новый дочерний класс
            //добавлять его в корневой элемент
            property.set('num', this.collection.length);

            var propertyView = new App.Views.Property({model:property});
            this.$el.append(propertyView.render().el);
        }
    });
    App.Views.AddProperty = Backbone.View.extend({
        el : "#addProperty",

        events : {
            'click' : 'submit'
        },

        initialize : function() {

        },

        submit : function(e){

            e.preventDefault();
            var newProperty = {
                                storage_events_id : 0,
                                properties_id: 0
            };
            newProperty.properties_id = parseInt(App.Attributes.PropertyList[0].id);
            newProperty.storage_events_id = ($('#storage_events_id_main').val().length) ? parseInt($('#storage_events_id_main').val()) : 0;
            var newView = new App.Models.Property(newProperty);
            this.collection.add(newView);
        }
    });


}());





