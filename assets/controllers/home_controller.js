 


import { Controller } from '@hotwired/stimulus';
import ReactDOM from 'react-dom/client'
import React from 'react'


export default class extends Controller {
    connect() {
        console.log('vrefer')
        ReactDOM.createRoot(this.element).render("dfvdfvdf")
        } 
}
