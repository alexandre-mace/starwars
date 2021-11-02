import {h, render} from 'preact';
import ConfigForm from "./components/ConfigForm";
import {useState} from "preact/compat";

const App = () => {
    const [odds, setOdds] = useState(null);

    return (
        <div className="App">
            <ConfigForm setOdds={setOdds}/>
            <br/>
            <div>Odds : {(odds !== null) ? odds : '?'}</div>
        </div>
    );
};

render(<App />, document.getElementById('app'));