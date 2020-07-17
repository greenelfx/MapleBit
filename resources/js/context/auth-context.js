import React from 'react'
import { queryCache } from 'react-query';
import { useAsync } from '../utils/hooks';
import { client } from '../utils/api-client';
import FullPageSpinner from '../components/FullPageSpinner';

const localStorageKey = '__auth_provider_token__'

function getToken() {
    return window.localStorage.getItem(localStorageKey)
}

async function setToken(token) {
    window.localStorage.setItem(localStorageKey, token);
}

async function bootstrapAppData() {
    let user = null
    const token = await getToken()
    if (token) {
        const data = await client('user/me', { token })
        //   queryCache.setQueryData('list-items', data.listItems, {
        //     staleTime: 5000,
        //   })
        user = data.user
    }
    return user
}

const AuthContext = React.createContext()
AuthContext.displayName = 'AuthContext'

function AuthProvider(props) {
    const {
        data: user,
        status,
        error,
        isLoading,
        isIdle,
        isError,
        isSuccess,
        run,
        setData,
    } = useAsync()

    React.useEffect(() => {
        const appDataPromise = bootstrapAppData()
        run(appDataPromise)
    }, [run])

    const login = React.useCallback(
        form => client('auth/login', { data: form }).then(data => {
            setToken(data.token);
            setData(data.user);
        }),
        [setData],
    )
    const register = React.useCallback(
        form => client('auth/create', {data: form}).then(data => {
            setToken(data.token);
            setData(data.user);
        }),
        [setData],
    )
    const logout = React.useCallback(() => {
        window.localStorage.removeItem(localStorageKey)
        queryCache.clear()
        setData(null)
    }, [setData])

    const value = React.useMemo(() => ({ user, login, logout, register }), [
        login,
        logout,
        register,
        user,
    ])

    if (isLoading || isIdle) {
        return <FullPageSpinner />
    }

    if (isSuccess) {
        return <AuthContext.Provider value={value} {...props} />
    }

    throw new Error(`Unhandled status: ${status}`)
}


function useAuth() {
    const context = React.useContext(AuthContext)
    if (context === undefined) {
        throw new Error(`useAuth must be used within a AuthProvider`)
    }
    return context
}

function useClient() {
    const token = getToken()
    return React.useCallback(
        (endpoint, config) => client(endpoint, { ...config, token }),
        [token],
    )
}

export { AuthProvider, useAuth, useClient }