import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { PopularDecksPage } from './popular-decks';

@NgModule({
  declarations: [
    PopularDecksPage,
  ],
  imports: [
    IonicPageModule.forChild(PopularDecksPage),
  ],
  exports: [
    PopularDecksPage
  ]
})
export class PopularDecksPageModule {}
