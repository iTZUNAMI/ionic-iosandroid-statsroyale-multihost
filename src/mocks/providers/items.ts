import { Injectable } from '@angular/core';
//import { Http } from '@angular/http';

import { Item } from '../../models/item';
import { Storage } from '@ionic/storage';

@Injectable()
export class Items {



  items: Item[] = [];


  defaultItem : any;

  

  constructor(private storage: Storage) {

//this.storage.clear();

              //carico items dal db
              this.storage.get('items').then((valitems) => {
                if (valitems) {

                  for (let item of valitems) {
                      this.items.push(new Item(item));
                     }
                 }
                
              });


  }
   

  query(params?: any) {


    if (!params) {
        return this.items;
      }
     
    

    return this.items.filter((item) => {
      for (let key in params) {
        let field = item[key];
        if (typeof field == 'string' && field.toLowerCase().indexOf(params[key].toLowerCase()) >= 0) {
          return item;
        } else if (field == params[key]) {
          return item;
        }
      }
      return null;
    });
  }

  add(item: Item) {
    this.items.push(item);
    console.log('adddd');
    this.aggiornadb();

  }

  delete(item: Item) {
    this.items.splice(this.items.indexOf(item), 1);
    console.log('delll');
    this.aggiornadb();
  }



  aggiornadb(){
    console.log('aggiornadb');
     this.storage.set('items',this.items);

  }




}
