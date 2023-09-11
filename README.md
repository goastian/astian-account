## SANGHAS
**API REST** desarrollada con la finalidad de proporcionar una solucion en el control de registros, reservas, pagos, que realizan en un hospedaje.
 
### Eventos 

**Login** `Channel` => `spondylus`
- LoginEvent 

**Administrar tokens** `Channel` => `spondylus.{id}` 
- DestroyAllTokenEvent 
- DestroyTokenEvent
- StoreTokenEvent
  
**Generacion de usuarios** `Channel` => `spondylus`
- DestroyEmployeeRoleEvent
- StoreEmployeeRoleEvent
- DisableEmployeeEvent
- EnableEmployeeEvent
- StoreEmployeeEvent
- UpdateEmployeeEvent
 
**Categories** `Channel` => `spondylus` 
  - DestroyCategoryEvent
  - DisableCategoryEvent
  - EnableCategoryEvent
  - StoreCategoryEvent
  - UpdateCategoryEvent

**Rooms** `Channel` => `spondylus` 
  - DestroyRoomEvent
  - DisableRoomEvent
  - EnableRoomEvent
  - StoreRoomEvent
  - UpdateRoomEvent

**Categories Calendar** `Channel` => `spondylus` 
  - StoreCategoryCalendarEvent
  - UpdateCategoryCalendarEvent

**Generacion de registros** `Channel` => `spondylus`
  - UpdateBookingEvent
  - StoreBookingEvent
  - DeleteBookingEvent

  - **Gestionar habitaciones en el registro** `Channel` => `spondylus`
    -   DestroyBookingRoomClientEvent
    -   StoreBookingRoomEvent
    -   UpdateBookingRoomEvent
  
  - **Gestionar Cliente** `Channel` => `spondylus`
    - DestroyBookingRoomClientEvent
    - StoreBookingRoomClientEvent
    - UpdateBookingRoomClientEvent
  
  - **Gestionar de empresa** `Channel` => `spondylus`
    - StoreBookingCompanyEvent
    - UpdateBookingCompanyEvent
  
  - **Gestionar pagos** 
    - UpdateBookingRoomClientEvent
    - StoreBookingRoomClientEvent
  
**Account** `Channel` => `spondylus` 
  - StoreAccountingEvent 
  - UpdateAccountingEvent