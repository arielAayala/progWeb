"use client"

import { useRouter } from "next/navigation"
import {useAuthContext} from "@/context/authContext"

function DashboardPage() {

  const router = useRouter()
  const {user, cerrarSesion} = useAuthContext()

  if (!user) {
      router.push("/")
  }

  return (
    <section className="h-[calc(100vh-7rem)] flex justify-center items-center">
    <div>
      <h1 className="text-white text-5xl">Dashboard</h1>
      <button className="bg-white text-black px-4 py-2 rounded-md mt-4"
        onClick={() => cerrarSesion()}
      >
        Cerrar Sesion
      </button>
    </div>
    </section>

  )
}
export default DashboardPage