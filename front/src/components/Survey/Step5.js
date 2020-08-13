import React, { Component } from 'react';
import { Form, Col, Button } from 'react-bootstrap';
import './styles.scss';
import ErrorField from '../Utils/Field/ErrorField';

export class Step5 extends Component {
  continue = (e) => {
    e.preventDefault();
    this.props.nextStep();
  };

  back = (e) => {
    e.preventDefault();
    this.props.prevStep();
  };

  render() {
    const { handleChange, CheckContentTrue, CheckContentFalse, CheckContent, handleAsNumber, sendData, sendDataReturn } = this.props;
    return (
      <div className="fullform">
        <div>
          <h2>Question Specifique</h2>
        </div>
        <div>
          <h1>Pour mieux vous servir nous aurions besoin d’en savoir plus sur vos besoin.</h1>
        </div>
        <div>
          <h2>Quel est le plus important pour vous ?</h2>
        </div>
        { sendData().specImportant !== undefined && <ErrorField error={sendData().specImportant[0]} /> }
        <Form>
          {['checkbox'].map((type) => (
            <div key={`inline-${type}`} className="mb-3">
              <Form.Check inline name="La silence" label="Le silence" type={type} id={`inline-${type}-1`} onChange={CheckContent('spec_important')} />
              <Form.Check inline name="Un juste milieux" label="Un juste milieux" type={type} id={`inline-${type}-2`} onChange={CheckContent('spec_important')} />
              <Form.Check inline name="Les performances" label="Les performances" type={type} id={`inline-${type}-3`} onChange={CheckContent('spec_important')} />
            </div>
          ))}
        </Form>
        <div>
          <h2>Voulez vous faire un SLI</h2>
        </div>
        <Form.Row>
          <Col>
            <Button name="yes" className="Form__button" variant="primary" type="submit" onClick={CheckContentTrue('spec_sli')}> Oui  </Button>
          </Col>
          <Col>
            <Button name="no" className="Form__button" variant="primary" type="submit" onClick={CheckContentFalse('spec_sli')}> Non </Button>
          </Col>
        </Form.Row>
        { sendData().specSli !== undefined && <ErrorField error={sendData().specSli[0]} /> }
        <div>
          <h2>Voulez vous pouvoir effectuer de l'overclocking ?</h2>
        </div>
        <Form.Row>
          <Col>
            <Button name="yes" className="Form__button" variant="primary" type="submit" onClick={CheckContentTrue('spec_overclock')}> Oui  </Button>
          </Col>
          <Col>
            <Button name="no" className="Form__button" variant="primary" type="submit" onClick={CheckContentFalse('spec_overclock')}> Non </Button>
          </Col>
        </Form.Row>
        { sendData().specOverclock !== undefined && <ErrorField error={sendData().specOverclock[0]} /> }
        <div>
          <h2>Parlons stockage voulez vous ?</h2>
          { sendData().specStorage !== undefined && <ErrorField error={sendData().specStorage[0]} /> }
        </div>
        <Form>
          {['checkbox'].map((type) => (
            <div key={`inline-${type}`} className="mb-3">
              <Form.Check name="HDD" inline label="HDD" type={type} id={`inline-${type}-1`} onChange={CheckContent('spec_storage')} />
              <Form.Check name="SSD" inline label="SSD" type={type} id={`inline-${type}-2`} onChange={CheckContent('spec_storage')} />
              <Form.Check name="SSHD" inline label="SSHD" type={type} id={`inline-${type}-3`} onChange={CheckContent('spec_storage')} />
              <Form.Check name="NVME" inline label="NVME" type={type} id={`inline-${type}-4`} onChange={CheckContent('spec_storage')} />
            </div>
          ))}
          <Form.Row>
            <Col>
              <Form.Label>Combien ?</Form.Label>
            </Col>
            <Col>
              <Form.Control type="number" placeholder="- GO" defaultValue={sendDataReturn().spec_storage_quantity} onChange={handleAsNumber('spec_storage_quantity')} />
            </Col>
          </Form.Row>
          { sendData().specStorageQuantity !== undefined && <ErrorField error={sendData().specStorageQuantity[0]} /> }
        </Form>

        <div>
          <h2>En ce qui concerne le wi-fi, voulez vous une carte ?</h2>
        </div>
        <Form.Row>
          <Col>
            <Button name="yes" className="Form__button" variant="primary" type="submit" onClick={CheckContentTrue('spec_wifi')}> Oui </Button>
          </Col>
          <Col>
            <Button name="no" className="Form__button" variant="primary" type="submit" onClick={CheckContentFalse('spec_wifi')}> Non </Button>
          </Col>
        </Form.Row>
        { sendData().specWifi !== undefined && <ErrorField error={sendData().specWifi[0]} /> }
        <div>
          <h2>Si oui, etes vous dans la meme piece que le router ?</h2>
        </div>
        <Form.Row>
          <Col>
            <Button name="yes" className="Form__button" variant="primary" type="submit" onClick={CheckContentTrue('spec_wifi_room')}> Oui </Button>
          </Col>
          <Col>
            <Button name="no" className="Form__button" variant="primary" type="submit" onClick={CheckContentFalse('spec_wifi_room')}> Non </Button>
          </Col>
        </Form.Row>
        { sendData().specWifiRoom !== undefined && <ErrorField error={sendData().specWifiRoom[0]} /> }
        <div>
          <h2>Si oui, avez vous la fibre optique ?</h2>
        </div>
        <Form.Row>
          <Col>
            <Button name="yes" className="Form__button" variant="primary" type="submit" onClick={CheckContentTrue('spec_fiber')}> Oui </Button>
          </Col>
          <Col>
            <Button name="no" className="Form__button" variant="primary" type="submit" onClick={CheckContentFalse('spec_fiber')}> Non </Button>
          </Col>
        </Form.Row>
        { sendData().specFiber !== undefined && <ErrorField error={sendData().specFiber[0]} /> }
        <div>
          <h2>En ce qui concerne le son, voulez vous une carte ?</h2>
        </div>
        <Form.Row>
          <Col>
            <Button name="yes" className="Form__button" variant="primary" type="submit" onClick={CheckContentTrue('spec_sound')}> Oui </Button>
          </Col>
          <Col>
            <Button name="no" className="Form__button" variant="primary" type="submit" onClick={CheckContentFalse('spec_sound')}> Non </Button>
          </Col>
        </Form.Row>
        { sendData().specSound !== undefined && <ErrorField error={sendData().specSound[0]} /> }
        <Form>
          <Form.Label>Pour quel utilisation ?</Form.Label>
          { sendData().specSoundUtilisation !== undefined && <ErrorField error={sendData().specSoundUtilisation[0]} /> }
          { sendData().specSoundUtilisationOther !== undefined && <ErrorField error={sendData().specSoundUtilisationOther[0]} /> }
          {['checkbox'].map((type) => (
            <div key={`inline-${type}`} className="mb-3">
              <Form.Check name="Jeux" inline label="Jeux" type={type} id={`inline-${type}-1`} onChange={CheckContent('spec_sound_utilisation')} />
              <Form.Check name="Home cinema" inline label="Home cinéma" type={type} id={`inline-${type}-2`} onChange={CheckContent('spec_sound_utilisation')} />
              <Form.Check name="Musique" inline label="Musique" type={type} id={`inline-${type}-3`} onChange={CheckContent('spec_sound_utilisation')} />
              <Form.Check name="Autres" inline label="Autres" type={type} id={`inline-${type}-4`} onChange={CheckContent('spec_sound_utilisation')} />
              <Form.Control placeholder="Précisez" defaultValue={sendDataReturn().spec_sound_utilisation_other} onChange={handleChange('spec_sound_utilisation_other')} />
            </div>
          ))}
        </Form>

        <Form>
          <Form.Label>Voulez vous un design particulier sur votre tour ? (lumieres,led,couleur harmonieuse, ..)</Form.Label>
          <Form.Row className="contact">
            <Form.Control as="textarea" defaultValue={sendDataReturn().spec_light} placeholder="Message" onChange={handleChange('spec_light')} />
          </Form.Row>
        </Form>

        <Form.Row>
          <Col>
            <Button className="Form__button" variant="primary" type="submit" onClick={this.back}> Precedent </Button>
          </Col>
          <Col>
            <Button className="Form__button" variant="primary" type="submit" onClick={this.continue}> Suivant </Button>
          </Col>
        </Form.Row>
      </div>
    );
  }
}

export default Step5;
