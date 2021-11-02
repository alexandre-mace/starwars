import {h} from 'preact';
import {useState} from "preact/compat";
import submitConfigForm from "../api/submitConfigForm";

const ConfigForm = ({ setOdds }) => {
    const [selectedFile, setSelectedFile] = useState(null);

    const submitForm = (e) => {
        e.preventDefault()
        const data = new FormData()
        data.append('file', selectedFile)
        submitConfigForm(data, setOdds)
    };

    const onChangeHandler= (event) => {
        setSelectedFile(event.target.files[0]);
    }

    return (
        <form>
            <input type="file" name="file" onChange={onChangeHandler}/>
            <button onClick={submitForm}>Submit</button>
        </form>
    )
}
export default ConfigForm;