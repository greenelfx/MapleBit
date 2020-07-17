import React from 'react';
import Card from 'react-bootstrap/Card';
import Spinner from 'react-bootstrap/Spinner';
import UserPanel from './UserPanel';
import { useQuery, queryCache } from 'react-query';
import { client } from '../utils/api-client';

  async function getServerInfo() {
    return await client('serverInfo');
  }

  
function Sidebar() {
    const { data, isLoading, error } = useQuery('serverInfo', getServerInfo)

    return (
        <>
            <Card>
                <Card.Header>Account</Card.Header>
                <UserPanel />
            </Card>
            <Card className='mt-4 mb-4'>
                <Card.Header>Server Info</Card.Header>
                <Card.Body>
                { isLoading ? (
                    <div className="text-center">
                    <Spinner animation="border" role="status">
                    <span className="sr-only">Loading...</span>
                  </Spinner>
                  </div>
                ) : error ? (<b>There's an error: {error.message}</b>) : data ? (
                    
                    <>
                        Players Online: <b>var</b><br />
                        Accounts: <b>var</b><br />
                        Characters: <b>var</b><br />
                        <hr />
                        Version: <b>{data['data']['server_data']['version']}</b><br />
                        Experience Rate: <b>{data['data']['server_data']['experience_rate']}x</b><br />
                        Meso Rate: <b>{data['data']['server_data']['meso_rate']}x</b><br />
                        Drop Rate: <b>{data['data']['server_data']['drop_rate']}x</b><br />
                    </>
                    ) : null}
                </Card.Body>
            </Card>
        </>
    );

}

export default Sidebar;