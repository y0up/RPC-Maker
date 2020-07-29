/* eslint-disable default-case */
/* eslint-disable react/jsx-indent */
import React, { Component } from 'react';
import Step1 from './Step1';
import Step2 from './Step2';
import Step3 from './Step3';
import Step4 from './Step4';
import Step5 from './Step5';
import Step6 from './Step6';
import Step7 from './Step7';
import Inscription from './inscription';
import './styles.scss';

export class UserForm extends Component {
  state = {
    step: 1,
  };

  nextStep = () => {
    const { step } = this.state;
    this.setState({
      step: step + 1,
    });
  };

  prevStep = () => {
    const { step } = this.state;
    this.setState({
      step: step - 1,
    });
  };

   // Handle fields change
   handleChange = (input) => (e) => {
     this.setState({ [input]: e.target.value });
   };

   render() {
     const { step } = this.state;

     switch (step) {
       case 1:
         return (
          <Step1
            nextStep={this.nextStep}
          />
         );
       case 2:
         return (
           <Step2
             nextStep={this.nextStep}
             prevStep={this.prevStep}
           />
         );
       case 3:
         return (
            <Step3
              nextStep={this.nextStep}
              prevStep={this.prevStep}
            />
         );
       case 4:
         return (
             <Step4
               nextStep={this.nextStep}
               prevStep={this.prevStep}
             />
         );
       case 5:
         return (
            <Step5
              nextStep={this.nextStep}
              prevStep={this.prevStep}
            />
         );
       case 6:
         return (
             <Step6
               nextStep={this.nextStep}
               prevStep={this.prevStep}
             />
         );
       case 7:
         return (
              <Step7
                nextStep={this.nextStep}
                prevStep={this.prevStep}
              />
         );
       case 8:
         return (
               <Inscription
                 nextStep={this.nextStep}
                 prevStep={this.prevStep}
               />
         );
     }
   }
}

export default UserForm;