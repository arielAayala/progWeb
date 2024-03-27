"use client";
import React, {
	useContext,
	createContext,
	useState,
	useMemo,
	useCallback,
} from "react";


const authContext = createContext({
	user: null,
});

function AuthContextProvider({ children }: {children: React.ReactNode}) {


	const [user, setUser] = useState(null);

	const iniciarSesion = useCallback((inputs: {nameUser:string, password:string}) => {
		fetch("http://localhost/progWeb/api/SignIn.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		credentials: "include",
		body: JSON.stringify(inputs),
	})
			.then(async (res) => {
				if (!res.ok) {
					throw new Error("ocurrio un error", {
						cause: await res.json(),
					});
				}
				return res.json();
			})
			.then((res) => {
				setUser(res.data);
				console.log(res.msg);
				
			})
			.catch((error) => {
				const errorMessage = error.cause?.msg || "Error desconocido";
				console.log(errorMessage);
			});
	}, []);

	const cerrarSesion = useCallback(() => {
		fetch("http://localhost/progWeb/api/LogOut.php", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
			},
			credentials: "include",
		})
			.then((res) => {
				if (!res.ok) {
					throw new Error("Ocurrio un error");
				}
				return res.json();
			})
			.then((res) => {
				setUser(null);
			})

			.catch((error) => {
				setUser(null);
			});
	}, []);

	const values = useMemo(
		() => ({
			user,
			iniciarSesion,
			cerrarSesion,
		}),
		[user, iniciarSesion, cerrarSesion]
	);

	return <authContext.Provider value={values}>{children}</authContext.Provider>;
}

const useAuthContext = () => useContext(authContext);

export { AuthContextProvider, useAuthContext };