const submitConfigForm = (data, setOdds) => {
    fetch('http://localhost:8000/compute', {
        method: 'post',
        body: data
    }).then(res => res.json())
        .then(res => setOdds(res));
}

export default submitConfigForm